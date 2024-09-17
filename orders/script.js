$(document).ready(function () {
    getorders();
    pagination_li();
    view_order();
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
        getorders();
        $this.parent().siblings().removeClass("active");
        $this.parent().addClass("active");
    });
}

function view_order() {
    $(document).on("click", '#view_order', function () {
        var id = $(this).data('id');

        sessionStorage.setItem("order_id", id);
        window.location.href = "../order_details/index.php";

    })
}

function getorders() {
    var pageno = $("#currentpage").val();
    var status = $("#filter").val();
    console.log('stasvsafasf status', parseInt(status));
    
    console.log('inside');
    $.ajax({
        url: "ajax.php",
        type: "GET",
        dataType: "json",
        data: { page: pageno, status: parseInt(status), action: "getorder" },
        beforeSend: function () {
            $("#overlay").fadeIn();
        },
        success: function (rows) {
            console.log(rows);
            console.log('hiii');

            if (rows.order) {
                var orderlist = "";
                $.each(rows.order, function (index, order) {
                    orderlist += getordersrow(index, order, pageno);

                });

                $("#ordertable tbody").html(orderlist);



                let totalorders = rows.count;
                let totalpages = Math.ceil(parseInt(totalorders) / 15);

                console.log(totalorders, totalpages);
                const currentpage = $("#currentpage").val();
                console.log(currentpage);
                pagination(totalpages, currentpage);
                $("#overlay").fadeOut();
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert("Error 124 : " + thrownError);
            console.error('AJAX Error:', xhr.status, thrownError);
            console.error('Response Text:', xhr.responseText);
        },
    });
}






// get player row
function getordersrow(index, order, page) {
    var orderRow = "";
    if (order) {

        const getstatus = (status) => {
            switch(status) {
                case 1:
                    return `<label class="badge badge-warning">Pending</label>`;
                case 2:
                    return `<label class="badge badge-success">Delivered</label>`;
                case 3:
                    return `<label class="badge badge-danger">Cancelled</label>`;
            }
        };
        const sl= 15*(page-1)+index+1;
        orderRow = `<tr>
          <td data-target="id">${sl}</td>
            <td class="text-center" data-target="colorname">${order.shopname}</td>
            <td class="text-center" data-target="id">${order.username}</td>
            <td class="text-center" data-target="colorname">${order.phonenumber}</td>
            <td class="text-center" data-target="colorcode">${order.orderdate}</td>
            <td class="text-center" data-target="colorcode">${order.totalamount}</td>
            <td class="text-center" data-target="colorname">${getstatus(order.status)}</td>
            <td>
                <button type="button" id="view_order" class="btn btn-success mr-2 mb-2" data-toggle="modal"
                    data-target="#userViewModal" data-id="${order.id}" title="Edit">
                    View Order
                </button>
                
            </td>
          </tr>`;
    }
    return orderRow;
}


