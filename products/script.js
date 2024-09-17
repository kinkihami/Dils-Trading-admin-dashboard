$(document).ready(function () {
  add_data();
  get_data();
  getproducts();
  pagination_li();
  add_image();
  variant_btn();
  getunits();
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
  $(document).on("click", '#addproductbtn', function () {
    var catid = sessionStorage.getItem('product_id');
    var name = $('#product_name').val();
    var price = $('#product_price').val();
    var desc = $('#product_desc').val();
    var unit = $('#unitselect').val();
    var image = $('#product_image').val();
    $('#catid').val(catid);
    var product_id = $('#product_id').val();
    

    if(product_id == ""){
      if (name == "" || image == "" || price == "" || unit == null || desc == "") {
        $('#msggg').html("<p class='alert alert-danger'>Please fill all the fields</p>");
      } else {
        var form_data = new FormData(document.getElementById("addform"));
        $.ajax({
          url: "ajax.php",
          method: "POST",
          data: form_data,
          processData: false, // Tell jQuery not to process the data
          contentType: false,
          success: function (response) {
            $('#msggg').html(response);
            console.log(response);
            if (response) {
              showdialog(false);
              getproducts();
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
    }else {
      var form_data = new FormData(document.getElementById("addform"));
      $.ajax({
        url: "ajax.php",
        method: "POST",
        data: form_data,
        processData: false, // Tell jQuery not to process the data
        contentType: false,
        success: function (response) {
          $('#msggg').html(response);
          console.log(response);
          if (response) {
            showdialog(false);
            getproducts();
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


function add_image() {
  $(document).on("click", '#add_image', function () {

    var id = $(this).data('id');
    sessionStorage.setItem('variant_id', id);
    window.location.href = "../add_product_images/index.php";

  })
}

function variant_btn() {
  $(document).on("click", '#variant_btn', function () {

      var id = $(this).data('id');
      sessionStorage.setItem('variant_id', id);
      window.location.href = "../variants/index.php";
  })
}

function getunits() {
 
  $.ajax({
    url: "ajax.php",
    type: "GET",
    dataType: "json",
    data: { action: "getunits" },
    beforeSend: function () {
      $("#overlay").fadeIn();
    },
    success: function (rows) {
     
      $.each(rows.units, function(index, item) {
          console.log(item.unit);
          
      
        if(item.id != 0){
          $('#unitselect').append($('<option>', {
            value: item.id,
            text: item.unit
        }));
        }
    });

    $('#unitselect').append($('<option>', {
      value: 0,
      text: 'None of the above'
  }));
    
    },
    error: function (xhr, ajaxOptions, thrownError){
      alert("Error : "+thrownError);
      $("#overlay").fadeOut();
  }    ,
  });
}


function pagination_li() {
  // pagination
  $(document).on("click", "ul.pagination li a", function (e) {
    e.preventDefault();
    var $this = $(this);
    const pagenum = $this.data("page");
    $("#currentpage").val(pagenum);
    getproducts();
    $this.parent().siblings().removeClass("active");
    $this.parent().addClass("active");
  });
}

function get_data() {
  $(document).on("click", '#product_edit', function () {
    var id = $(this).data('id');
    
    $.ajax({
      url: "ajax.php",
      type: "GET",
      dataType: "json",
      data: { id: id, action: "getproductfields" },
      beforeSend: function () {
        $("#overlay").fadeIn();
      },
      success: function (product) {
        if (product) {
          $('#product_name').val(product.productname);
          $('#product_price').val(product.price);
          $('#product_desc').val(product.description);
          $('#fast_moving').prop('checked', product.fast_moving == 1);
          $('#unitselect').val(product.unitid);
          $('#product_id').val(product.id);
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



function getproducts() {
  var pageno = $("#currentpage").val();
  var id = sessionStorage.getItem('product_id');
  console.log('id=', id);
  $.ajax({
    url: "ajax.php",
    type: "GET",
    dataType: "json",
    data: { page: pageno, id: id, action: "getproduct" },
    beforeSend: function () {
      $("#overlay").fadeIn();
    },
    success: function (rows) {
      console.log(rows);
      console.log('hiii');

      if (rows.product) {
        var productlist = "";
        $.each(rows.product, function (index, product) {
          productlist += getproductsrow(index, product, pageno);

        });

        $("#producttable tbody").html(productlist);



        let totalproducts = rows.count;
        let totalpages = Math.ceil(parseInt(totalproducts) / 15);

        console.log(totalproducts, totalpages);
        const currentpage = $("#currentpage").val();
        console.log(currentpage);
        pagination(totalpages, currentpage);
        $("#overlay").fadeOut();
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert("Error: " + thrownError);   
      console.error('AJAX Error:', xhr.status, thrownError);
      console.error('Response Text:', xhr.responseText);
    },
  });
}


// get player row
function getproductsrow(index, product, page) {
  var productRow = "";
  if (product) {


    const fastmoving = (fresh) => {
      switch(fresh) {
          case 0:
              return `No`;
          case 1:
              return `Yes`;
      }
  };

  const sl= 15*(page-1)+index+1;


    // const userphoto = player.photo ? player.photo : "default.png";
    productRow = `<tr>
            <td data-target="id">${sl}</td>
                <td class="text-center" data-target="cat_name">${product.productname}</td>
                <td class="text-center" data-target="parent_category_id"><img width="100" height="100" src="../product_image/${product.image}" ></td>
                <td class="text-center" data-target="cat_name">${product.price}</td>-
                <td class="text-center" data-target="cat_name">${product.unit}</td>-
                <td class='text-center' data-target="cat_image">${product.description}</td>
                <td class="text-center" data-target="parent_product_id">${fastmoving(product.fast_moving)}</td>
                  <td>
                      <button type="button" id="product_edit" class="btn btn-warning mr-2"
                          data-toggle="modal" data-target="#userViewModal" data-id="${product.id}" title="Edit">
                          Edit
                      </button>
                      <button type="button" id="add_image" class="btn btn-secondary mr-2"
                          data-toggle="modal" data-target="#userViewModal" data-id="${product.id}" title="Edit">
                          Add Image
                      </button>
                      <button type="button" id="variant_btn" class="btn btn-info mr-2"
                          data-toggle="modal" data-target="#userViewModal" data-id="${product.id}" title="Edit">
                          Add Variant
                      </button>
                    
                  </td>
          </tr>`;
  }
  return productRow;
}