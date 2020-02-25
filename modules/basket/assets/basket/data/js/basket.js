
var notify = function(message, type='info'){
    if(bsVersion == 3) {
        $.notify(message, {type:type});
    }

    if(bsVersion >= 4){
        $.toast({
            // title: yii.t('global', 'notification'),
            content:message,
            type: type,
            delay: 5000
        });
    }
}


var Basket = function(options){

    var _options = {
        'onAddItem':null,
        'addToBasketClass':'.addToBasket'
    };

    this.construct = function(options){
        $.extend(_options, options);
    };

    this.construct(options);

    var _this = this;

    this.addItemClick = function(){

        $("body").on("click", _options.addToBasketClass, function(e){
            e.preventDefault();

            var id = $(this).data('id');
            var amountElem = $(this).data('amount-elem');
            amount = $(amountElem).val();
            amount = parseInt(amount, 10);
            if(amount <= 0){
                amount = 1;
            }

            $.getJSON("/basket/cart/put?id="+id+"&amount="+amount, function(response){
                notify(response.message, response.success?'success':'danger');

                if(response.success) {
                    if (typeof (_options.onAddItem) == 'function') {
                        _options.onAddItem(response);
                    }
                }
            }).catch(function(response){
                notify(response.responseText, 'danger');
            })

            return false;
        });
    }

    this.init = function(){
        this.addItemClick();

    }

    this.init();

}

var basketOld = {
    onAddItem:function(callbackFunction){

        console.log(callbackFunction);

    },
    init:function(){


        $("body").on("click", ".addToBasket", function(e){
            e.preventDefault();

            var id = $(this).data('id');
            var amountElem = $(this).data('amount-elem');
            amount = $(amountElem).val();
            amount = parseInt(amount, 10);
            if(amount <= 0){
                amount = 1;
            }

            $.getJSON("/basket/cart/put?id="+id+"&amount="+amount, function(response){

                // if(response.success){
                //     $(".cart_quantity").html(response.total_amount);
                // }

                notify(response.message);





            }).catch(function(response){

                notify(response.responseText, 'danger');

            })

            return false;
        })
    }
}

