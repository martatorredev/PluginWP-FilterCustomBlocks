jQuery(document).ready(function($){

    $(document).on('click', '.item-cat-pfolio', function(){

        let $this = $(this);
        let ide = $(this).attr('data-id');
        let catCurrent = $(this).attr('data-cat');
        $('.gr_grid').empty();
        let html = '<div class="gr_fig"><figure class="load-img-mainbo"><img src="'+ pfolio_Public.loading +'"/></figure></div>';
        $('.gr_grid').html(html);

        $('.item-cat-pfolio').removeClass('on');
        $.ajax({
            url 	: pfolio_Public.url,
            method 	: 'POST',
            data 	: {
                ide   : ide,
                catCurrent   : catCurrent,
                action: 'action_loadpfolio',
            }, 
            beforeSend: function(){
                $this.addClass('on');
                console.info('cargando');
            },
            success: function( data ) {
                $('.gr_grid').html(data);
                console.log(data);
            },
            error: function(){
                console.warn('error');
            }
        });
  

    }); 


    const elementplus = document.getElementsByClassName('trid')[3];
    const elementminus = document.getElementsByClassName('trid')[2];
    elementplus.remove();
    elementminus.remove();


      
})




if(document.querySelector('.item-legend-1')){
	let ar = pfolio_Public.list;
	if(ar.includes('item-legend-1')){
		
	} else{ document.querySelector('.item-legend-1').classList.add('hidden') }
}

if(document.querySelector('.item-legend-2')){
	let ar = pfolio_Public.list;
	if(ar.includes('item-legend-2')){
		
	} else{ document.querySelector('.item-legend-2').classList.add('hidden') }
}

if(document.querySelector('.item-legend-3')){
	let ar = pfolio_Public.list;
	if(ar.includes('item-legend-3')){
		
	} else{ document.querySelector('.item-legend-3').classList.add('hidden') }
}

if(document.querySelector('.item-legend-4')){
	let ar = pfolio_Public.list;
	if(ar.includes('item-legend-4')){
		
	} else{ document.querySelector('.item-legend-4').classList.add('hidden') }
}

if(document.querySelector('.item-legend-5')){
	let ar = pfolio_Public.list;
	if(ar.includes('item-legend-5')){
		
	} else{ document.querySelector('.item-legend-5').classList.add('hidden') }
}