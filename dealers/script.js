$(document).ready(function () {
  add_data();
  get_data();
  delete_data();
  getdealers();
  pagination_li();
});


// get pagination
function pagination(totalpages, currentpage) {
  var pagelist = "";
  if (totalpages > 1 || currentpage>1) {
    currentpage = parseInt(currentpage);
    pagelist += `<ul class="pagination justify-content-center">`;
    const prevClass = currentpage == 1 ? " disabled" : "";
    pagelist += `<li class="page-item${prevClass}"><a class="page-link" href="#" data-page="${
      currentpage - 1
    }">Previous</a></li>`;
    for (let p = 1; p <= totalpages; p++) {
      const activeClass = currentpage == p ? " active" : "";
      pagelist += `<li class="page-item${activeClass}"><a class="page-link" href="#" data-page="${p}">${p}</a></li>`;
    }
    const nextClass = currentpage == totalpages ? " disabled" : "";
    pagelist += `<li class="page-item${nextClass}"><a class="page-link" href="#" data-page="${
      currentpage + 1
    }">Next</a></li>`;
    pagelist += `</ul>`;
  }

  $("#pagination").html(pagelist);
}

function add_data() {
  $(document).on("click", '#adddealerbtn', function (event) {
    event.preventDefault();

    var alertmsg =
      $("#dealerid").val().length > 0
        ? "dealer has been updated Successfully!"
        : "New dealer has been added Successfully!";

        var owner = $('#owner').val();
        var shop = $('#shop').val();
        var phone = $('#phone').val();
        var address = $('#address').val();
        var gst = $('#gst').val();
        var id=$('#dealerid').val();

        const premiumCheckbox = document.getElementById('premium');
        
        // Set the checkbox value based on its checked status
        var premium = premiumCheckbox.checked ? '1' : '0';

        console.log('check box value ojjdhasdakdasd',premium);
        

    console.log(id);

    if (owner == "" || shop == "" || phone == ""||address==""||gst=="") {
      $('#msggg').html("<p class='alert alert-danger'>Please fill all the fields</p>");
    } else {
      $.ajax({
        url: "ajax.php",
        method: "POST",
        dataType: "json",
        data: { owner: owner, shop: shop,address: address,gst: gst, phone: phone, premium: premium, id: id, action: "adddealer" },
        success: function (response) {
          $('#msggg').html(response);
          console.log(response);
          if (response.success) {
            showdialog(false);
            getdealers();
            $("#overlay").fadeOut();
          }
          console.log('success');
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert("Error : " + thrownError);
          console.log('error');
        },
      })
    }
  })
}


function pagination_li(){
  // pagination
  $(document).on("click", "ul.pagination li a", function (e) {
    e.preventDefault();
    var $this = $(this);
    const pagenum = $this.data("page");
    $("#currentpage").val(pagenum);
    getdealers();
    $this.parent().siblings().removeClass("active");
    $this.parent().addClass("active");
  });
}

function get_data() {
  $(document).on("click", '#dealer_edit', function () {
    var id = $(this).data('id');
   
    $.ajax({
      url: "ajax.php",
      type: "GET",
      dataType: "json",
      data: { id: id, action: "getdealerfields" },
      beforeSend: function () {
        $("#overlay").fadeIn();
      },
      success: function (dealer) {
        if (dealer) {
          $('#owner').val(dealer.owner);
          $('#shop').val(dealer.shopname);
          $('#phone').val(dealer.phonenumber);
          $('#address').val(dealer.address);
          $('#gst').val(dealer.gstno);
          $('#premium').prop('checked', dealer.premium == 1);
          $('#dealerid').val(dealer.id);
          showdialog(true);
        }
        $("#overlay").fadeOut();
      },
      error: function (xhr, ajaxOptions, thrownError) {
        alert("Error : " + thrownError);
      },
    });
  })
}

function delete_data() {
  $(document).on("click", '#dealer_delete', function () {

    var a = confirm('Do you really want to delete this?');

    if (a) {
      var id = $(this).data('id');
      $.ajax({
        url: "ajax.php",
        type: "POST",
        dataType: "json",
        data: { id: id, action: "deletedealer" },
        beforeSend: function () {
          $("#overlay").fadeIn();
        },
        success: function (res) {
          console.log(res);
          if (res.deleted == 1) {
            $(".message")
              .html("dealer has been deleted successfully!")
              .fadeIn()
              .delay(3000)
              .fadeOut();
            getdealers();
            $("#overlay").fadeOut();
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert("Error : " + thrownError);
        },
      });
    }
  })
}

function getdealers() {
  var pageno = $("#currentpage").val();

  console.log('inside');
  $.ajax({
    url: "ajax.php",
    type: "GET",
    dataType: "json",
    data: {  page: pageno, action: "getdealer" },
    beforeSend: function () {
      $("#overlay").fadeIn();
    },
    success: function (rows) {
      console.log(rows);
      console.log('hiii');

      if (rows.dealer) {
        var dealerlist = "";
        $.each(rows.dealer, function (index, dealer) {
          dealerlist += getdealersrow(index, dealer, pageno);

        });

        $("#dealertable tbody").html(dealerlist);



        let totaldealers = rows.count;
        let totalpages = Math.ceil(parseInt(totaldealers) / 15);

        console.log(totaldealers, totalpages);
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


function getdealersrow(index, dealer, page) {
  var dealerRow = "";
  if (dealer) {

    const premium = (premium) => {
      switch(premium) {
          case 0:
              return `No`;
          case 1:
              return `Yes`;
      }
  };

  
  const sl= 15*(page-1)+index+1;
  
    dealerRow = `<tr>
         <td data-target="id">${sl}</td>
          <td class="text-center">${dealer.owner}</td>
          <td class="text-center">${dealer.shopname}</td>
          <td class="text-center">${dealer.phonenumber}</td>
          <td class="text-center">${dealer.address}</td>
          <td class="text-center">${dealer.gstno}</td>
                          <td class="text-center" data-target="parent_product_id">${premium(dealer.premium)}</td>
          <td>
                    <button type="button" id="dealer_edit" class="btn btn-warning mr-2"
                        data-toggle="modal" data-target="#userViewModal" data-id="${dealer.id}" title="Edit">
                        Edit
                    </button>
                    <button type="button" id="dealer_delete" class="btn btn-danger mr-2"
                        data-toggle="modal" data-target="#userViewModal" data-id="${dealer.id}" title="delete">
                        Delete
                    </button>
                </td>
        </tr>`;
  }
  return dealerRow;
}


  