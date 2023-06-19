const { registerBlockType } = wp.blocks;
const { withSelect } = wp.data;
const { RichText, InspectorControls } = wp.editor;
const { PanelBody, RangeControl, SelectControl, TextControl, CheckboxControl } = wp.components;
import { useState } from '@wordpress/element';
import Select from 'react-select';

registerBlockType('filter/products', {

    title: 'Filtro de productos',
    category: 'ondesarrollo',
    attributes: {
        numbers: {
            type: 'number'
        },
        categoryPost: {
            type: 'string'
        },
		selectedOption: {
			type: 'string',
			default: null,
		}
    },



    edit: withSelect( (select, props) => {



		let checkboxes = [];

    	const { attributes: { numbers, categoryPost, selectedOption }, setAttributes } = props;

		const handleSelectChange = ( selectedOption ) => setAttributes( { selectedOption: JSON.stringify( selectedOption ) } );

    	const onChangeCantidadMostrar = nuevaCantidad => {
      		setAttributes({ numbers: parseInt(nuevaCantidad) });
    	};

    	const onChangeCategoriaMenu = nuevaCategoria => {
      		setAttributes({ categoryPost: nuevaCategoria });
    	};



      	var query = {
      		'categories': categoryPost,
	        per_page: 4,
      	}

      	var a = select("core").getEntityRecords('postType', 'product', query);

	    return {
	      	categorias: select('core').getEntityRecords('taxonomy', 'product_cat', {per_page: 100}),

	      	// Enviar una petición a la api
	      	especialidades: select("core").getEntityRecords('postType', 'product', query),
	      	onChangeCantidadMostrar: onChangeCantidadMostrar,
	      	onChangeCategoriaMenu: onChangeCategoriaMenu,
			handleSelectChange: handleSelectChange,
	      	props: props
	    };
	})


	(({ categorias, especialidades, onChangeCantidadMostrar, onChangeCategoriaMenu, props, handleSelectChange})  => {

		const [ isChecked, setChecked ] = useState( true );

    	const { attributes: { numbers, categoryPost, selectedOption } } = props;

    	if (!especialidades) {
      		return 'Cargando...';
    	}

    	// Si no hay especialidades
    	if (especialidades && especialidades.length === 0) {
      		return 'No se encontraron artículos';
    	}

    	// Verificar categorias
    	if (!categorias) {
      		return null;
    	}

    	if (categorias && categorias.length === 0) {
      		return null;
    	}

    	// Generar label y value a categorias
    	categorias.forEach(function (categoria) {
      		categoria['label'] = categoria.name;
      		categoria['value'] = categoria.id;
    	});

    	// Arreglo con valores por default
    	const opcionDefault = [{
      		value: '',
      		label: ' -- All -- '
    	}];

    	const listadoCategorias = [ ...categorias ];


    	//Filtros

    	especialidades.sort(function(a, b) {
            return b['id'] - a['id'];
        })

        console.log(especialidades);

    	return(
            <>
                <InspectorControls>




                	<PanelBody
                    	title={'Categoria'}
                    	initialOpen={true}
                	>
	                    <div className="components-base-control">
	                        <div className="components-base-control__field">
	                            <label className="components-base-control__label">
	                            Seleccione categoria(s)
	                            </label>


								<Select
									name='select-two'
									value={ JSON.parse( selectedOption ) }
									onChange={ handleSelectChange }
									options={listadoCategorias}
									isMulti='true'
								/>



	                        </div>
	                    </div>
                	</PanelBody>



                </InspectorControls>

                <div className="gr_container_home">
                    <div className="gr_grid_home">
                        {especialidades.map(especialidad => (

                            <div>
                                <figure>
                                    <a><img src={especialidad.featured_image} /></a>
                                </figure>
                                <h2>{especialidad.title.rendered}</h2>
                            </div>

                        ))}

                    </div>
				</div>



            </>
        )

    }),




  	save: () => {
        return null;
    }

});