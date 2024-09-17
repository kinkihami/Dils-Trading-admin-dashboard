$(document).ready(function () {
    getowner();
    pagination_li();
});


// get pagination
function pagination(totalpages, currentpage) {
    var pagelist = "";
    if (totalpages > 1 || currentpage > 1) {
        currentpage = parseInt(currentpage);
        pagelist += `<ul class="pagination justify-content-center">`;
        const prevClass = currentpage == 1 ? " disabled" : "";
        pagelist += `<li class="page-item${prevClass}"><a class="page-link" href="#" data-page="${currentpage - 1
            }">Previous</a></li>`;
        for (let p = 1; p <= totalpages; p++) {
            const activeClass = currentpage == p ? " active" : "";
            pagelist += `<li class="page-item${activeClass}"><a class="page-link" href="#" data-page="${p}">${p}</a></li>`;
        }
        const nextClass = currentpage == totalpages ? " disabled" : "";
        pagelist += `<li class="page-item${nextClass}"><a class="page-link" href="#" data-page="${currentpage + 1
            }">Next</a></li>`;
        pagelist += `</ul>`;
    }

    $("#pagination").html(pagelist);
}


function pagination_li() {
    // pagination
    $(document).on("click", "ul.pagination li a", function (e) {
        e.preventDefault();
        var $this = $(this);
        const pagenum = $this.data("page");
        $("#currentpage").val(pagenum);
        getdeatils();
        $this.parent().siblings().removeClass("active");
        $this.parent().addClass("active");
    });
}


function getowner() {
    var  order_id=sessionStorage.getItem('order_id');
    console.log('fdsasdffgg', order_id);
    console.log('inside');
    $.ajax({
        url: "ajax.php",
        type: "GET",
        dataType: "json",
        data: { action: "getdetails", order_id:order_id },
        beforeSend: function () {
            $("#overlay").fadeIn();
        },
        success: function (rows) {
            console.log(rows);
            console.log('hiii');

            if (rows.details) {
                var ownerlist="";
                var detailslist = "";
                var totalAmount = 0;
                

                $.each(rows.details, function (index, details) {
                    detailslist += getproductsdetailsrow(index, details);
                    totalAmount += details.price * details.quantity;
                });

                detailslist += `<tr>
                    <td class="pl-5"><b class=""></b></td>
                    <td class="pl-4"><b class=""></b></td>
                    <td class="pl-4"><b class=""></b></td>
                    <td class="pl-4"><b class=""></b></td>
                    <td class="pl-0 text-center"><b class="">GRAND TOTAL</b></td>
                    <td class="pl-4"><b class="">${totalAmount.toFixed(2)}</b></td>
                </tr>`;

                $("#productdetailstable tbody").html(detailslist);

                    ownerlist += getownersrow(rows.details[0],totalAmount);
                

                $("#ownertable tbody").html(ownerlist);
                $('#status').val(rows.details[0].status);
                
            }
            
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert("Error 124 : " + thrownError);
            console.error('AJAX Error:', xhr.status, thrownError);
            console.error('Response Text:', xhr.responseText);
        },
    });
}


function invoice() {

        var order= $('#orderid').text();
        console.log(order);
        
        $.ajax({
            url: "invoice.php",
            type: "GET",
            data: { id: order },
            success: function(response) {
                console.log('Invoice generated successfully:', response);
                // window.location.href = "invoice.php";   
                window.open('invoice.php?id=' + order, '_blank');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert("Error : " + thrownError);
                console.error('AJAX Error:', xhr.status, thrownError);
                console.error('Response Text:', xhr.responseText);
                $("#overlay").fadeOut();
            },
        });    

}


function updatestatus(id) {
    console.log(id);
    var status = $('#status').val();
    var a = confirm('Do you really want to update the status?');
    console.log(status);
    

    if (a) {
    $.ajax({
        url: "ajax.php",
        type: "POST",
        dataType: "json",
        data: { id: id, status: status, action: "updatestatus" },
        beforeSend: function () {
            $("#overlay").fadeIn();
        },
        success: function (res) {
            console.log(res);
            if (res.success) {
                getowner();
                $("#overlay").fadeOut();
            } else {
                alert(res.message);
                $("#overlay").fadeOut();
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert("Error : " + thrownError);
            console.error('AJAX Error:', xhr.status, thrownError);
            console.error('Response Text:', xhr.responseText);
            $("#overlay").fadeOut();
        },
    });
}
}




// get player row
function getownersrow(details, totalamount) {
    var detailsRow = "";
    if (details) {


        detailsRow = `<tr>
            <td class="text-center" data-target="colorname">${details.shopname}</td>
            <td class="text-center" data-target="id">${details.owner}</td>
            <td class="text-center" data-target="colorname">${details.phonenumber}</td>
            <td class="text-center" data-target="colorcode">${details.address}</td>
            <td class="text-center" data-target="colorcode">${details.orderdate}</td>
            <td class="text-center" data-target="colorcode">${totalamount}</td>
            <td hidden id="orderid" data-target="">${details.id}</td>
            <td>
                                                <select class="form-control mr-2" id="status">
                                                <option value="1">Pending</option>
                                                <option value="2">Delivered</option>
                                                <option value="3">Cancelled</option> 
                                                </select>
                                                <button type="button" name="update" class="btn btn-success btn-sm" onclick="updatestatus(${details.id})">Update Status</button>
                                               
            </td>
          </tr>`;
          
    }
    return detailsRow;
}



function getproductsdetailsrow(index, details) {
    var detailsRow = "";
    if (details) {



        detailsRow = `<tr>
            <td class="text-center" data-target="colorname">${details.productname}</td>
            <td class="text-center" data-target="parent_category_id"><img width="100" height="100" src="../product_image/${details.image}" ></td>
            <td class="text-center" data-target="colorname">${details.size}</td>
            <td class="text-center" data-target="colorcode">${details.quantity}</td>
            <td class="text-center" data-target="colorcode">${details.price}</td>
            <td class="text-center"  id="top" data-target="colorcode">${details.price*details.quantity}</td>
          </tr>`;
    }
    return detailsRow;
}
