var retail_itemsArray = [];
$(function(){
    $("#potemplateform").validate({
		errorPlacement: function(error, element) {
			if (element.attr("name") == "po_avg_cycle") {
				error.insertAfter("#requestorPhoneLast");
			} else {
				error.insertAfter(element);
			}
		},
	});
});

function get_pro_branch(city_id){
    var product_id = $('#proid').val();
    var _token = $("meta[name='csrf-token']").attr("content");
    $.ajax({
        url: ajax_url+'/product_branch',
        type: "POST",
        data: {_token,city_id,product_id},
        success: function(response){
          $('.product-branch').html(response);
        }
    });
}
function GetSelectedproduct() {
    $('.retail-items').html('Please select a product line first.');
    var product_id = $('#proid').val();
    var corp_id = $('#corp_id').val();
    var _token = $("meta[name='csrf-token']").attr("content");
    var count_checked_branch = $("[name='branch[]']:checked").length; 
    var count_checked_product = $("[name='product_active[]']:checked").length;
    var ids = []
    $("input.product_active:checked").each(function ()
    {
        ids.push(parseInt($(this).val()));
    });
    if(ids.length > 0){
          $.ajax({
            url: ajax_url+'/retail_items',
            type: "POST",
            data: {_token, ids, product_id, corp_id ,retail_itemsArray},
            success: function(response){ 
                $('.retail-items').html(response);
                var count_checked_retailitem = $("[name='item_id[]']:checked").length;
                if(count_checked_branch > 0 && count_checked_retailitem > 0 && count_checked_product > 0){   
                    $(".save_button").removeAttr("disabled"); 
                }
            }
        });
    }
  
}

$(".city_name").change(function(){ 
	var city_id = $('#city_name option:selected').val();
	get_pro_branch(city_id);
});
$("#purchaseall").change(function(){
    $('.retail-items').html('Please select a product line first.');
    if(this.checked){ 
        $(".select").each(function(){
            this.checked=true;
        });
        GetSelectedproduct();              
    }else{
        $(".select").each(function(){
            this.checked=false;
            $('.retail-items').html('Please select a product line first.');
     
        });              
    }
    enablecheckbox();  
});
$(function(){
    var isSelected = [];
    $('.product_active').each(function() {
        if ($(this).is(":checked")) {
            isSelected.push("true");
        } else {
            isSelected.push("false");
        }
    });
    if ($.inArray("false", isSelected) < 0) {
        if(isSelected.length != 0){
            $(".purchase-all").attr("checked", true);
        }   
    }
});

function enablecheckbox(){
    var count_checked_branch = $("[name='branch[]']:checked").length; 
    var count_checked_retailitem = $("[name='item_id[]']:checked").length;
    var count_checked_product = $("[name='product_active[]']:checked").length;
    if(count_checked_branch > 0 && count_checked_retailitem > 0 && count_checked_product > 0){   
        $(".save_button").removeAttr("disabled");   
    }else{
        $(".save_button").attr('disabled', true); 
    }
}