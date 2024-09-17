$(document).ready(function () {
  add_data();
  get_data();
  getvariant();
  pagination_li();
  delete_variant();
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


function add_data() {
  $(document).on("click", '#addvariantbtn', function () {

    var size = $('#size').val();
    var price = $('#price').val();
    var productid = sessionStorage.getItem('variant_id');
    var id= $("#variant_id").val();


    if (size == "" || price == "") {
      $('#msggg').html("<p class='alert alert-danger'>Please fill all the fields</p>");
    } else {
      $.ajax({
        url: "ajax.php",
        method: "POST",
        data: {size:size, price: price, productid: productid, id: id, action: "addvariant"},
        success: function (response) {
          $('#msggg').html(response);
          console.log(response);
          if (response) {
            showdialog(false);
            getvariant();
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


function delete_variant() {
  $(document).on("click", '#delete_variant', function () {


    var a = confirm('Are you sure you want to disable this?');

    if (a) {
      var id = $(this).data('id');
      $.ajax({
        url: "ajax.php",
        type: "POST",
        dataType: "json",
        data: { id: id, action: "deletevariant" },
        beforeSend: function () {
          $("#overlay").fadeIn();
        },
        success: function (res) {
          console.log(res);
          if (res.success) {
            $(".message")
              .html("variant has been disabled successfully!")
              .fadeIn()
              .delay(3000)
              .fadeOut();
              getvariant();
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



function pagination_li() {
  // pagination
  $(document).on("click", "ul.pagination li a", function (e) {
    e.preventDefault();
    var $this = $(this);
    const pagenum = $this.data("page");
    $("#currentpage").val(pagenum);
    getvariant();
    $this.parent().siblings().removeClass("active");
    $this.parent().addClass("active");
  });
  // form reset on new button
  // $("#addnewbtn").on("click", function () {
  //   $("#addform")[0].reset();
  //   $("#variantid").val("");
  // });

  //  $("#addnewmessagebtn").on("click", function () {

  //   $("#addmessageform")[0].reset();
  //   $("#id").val("");
  // });

}

function get_data() {
  $(document).on("click", '#variant_edit', function () {
    var id = $(this).data('id');
    console.log(id);

    $.ajax({
      url: "ajax.php",
      type: "GET",
      dataType: "json",
      data: { id: id, action: "getvariantfields" },
      beforeSend: function () {
        $("#overlay").fadeIn();
      },
      success: function (variant) {
        if (variant) {
          $('#size').val(variant.size); 
          $('#price').val(variant.price); 
          $('#variant_id').val(variant.id);

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



function getvariant() {
  var pageno = $("#currentpage").val();
  var id = sessionStorage.getItem('variant_id');
  console.log('id=', id);
  $.ajax({
    url: "ajax.php",
    type: "GET",
    dataType: "json",
    data: { page: pageno, id: id, action: "getvariant" },
    beforeSend: function () {
      $("#overlay").fadeIn();
    },
    success: function (rows) {
      console.log(rows);
      console.log('hiii');

      if (rows.variant) {
        var variantlist = "";
        $.each(rows.variant, function (index, variant) {
          variantlist += getvariantrow(index, variant, pageno);

        });

        $("#variant_table tbody").html(variantlist);



        let totalvariant = rows.count;
        let totalpages = Math.ceil(parseInt(totalvariant) / 15);

        console.log(totalvariant, totalpages);
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
function getvariantrow(index, variant, page) {
  var variantRow = "";
  if (variant) {

   
  const sl= 15*(page-1)+index+1;

    // const userphoto = player.photo ? player.photo : "default.png";
    variantRow = `<tr>
            <td data-target="id">${sl}</td>
                <td class='text-center' data-target="cat_image">${variant.productname}</td>
                <td class="text-center" data-target="parent_category_id"><img width="100" height="100" src="../product_image/${variant.image}" ></td>
                <td class="text-center" data-target="cat_name">${variant.size}</td>
                <td class='text-center' data-target="cat_image">${variant.price}</td>
                  <td>
                      <button type="button" id="variant_edit" class="btn btn-warning mr-2"
                          data-toggle="modal" data-target="#userViewModal" data-id="${variant.id}" title="Edit">
                          Edit
                      </button>
                      <button type="button" id="delete_variant" class="btn btn-danger mr-2"
                          data-toggle="modal" data-target="#userViewModal" data-id="${variant.id}" title="Edit">
                          Delete
                      </button>                   
                  </td>
          </tr>`;
  }
  return variantRow;
}