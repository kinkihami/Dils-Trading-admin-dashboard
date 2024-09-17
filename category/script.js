$(document).ready(function () {
  add_data();
  get_data();
  getcategories();
  sub_category();
  pagination_li();
  products();
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
  $(document).on("click", '#addcategorybtn', function (event) {
    event.preventDefault();
    var name = $('#cat_name').val();
    var image=$('#bottom').val();
    var id= $('#cat_id').val();
    console.log(id);
    var form_data = new FormData(document.getElementById("addform"));
    if(id==""){
      if (name == ""||image=="") {
        $('#msggg').html("<p class='alert alert-danger'>Please fill all the fields</p>");
      } else {
        
        $.ajax({
          url: "ajax.php",
          method: "POST",
          dataType: "json",
          data: form_data,
          processData: false, // Tell jQuery not to process the data
          contentType: false, 
          success: function (response) {
            $('#msggg').html(response);
            console.log(response);
            if (response) {
              showdialog(false);
              getcategories();
              $("#overlay").fadeOut();
            }
            console.log('success');
          },
          error: function (xhr, ajaxOptions, thrownError) {
            alert("Error : " + thrownError);
            console.log('error');
            console.error('AJAX Error:', xhr.status, thrownError);
              console.error('Response Text:', xhr.responseText);
          },
        })
      }
    }
    else {
      
      $.ajax({
        url: "ajax.php",
        method: "POST",
        dataType: "json",
        data: form_data,
        processData: false, // Tell jQuery not to process the data
        contentType: false, 
        success: function (response) {
          $('#msggg').html(response);
          console.log(response);
          if (response) {
            showdialog(false);
            getcategories();
            $("#overlay").fadeOut();
          }
          console.log('success');
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert("Error : " + thrownError);
          console.log('error');
          console.error('AJAX Error:', xhr.status, thrownError);
            console.error('Response Text:', xhr.responseText);
        },
      })
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
    getcategories();
    $this.parent().siblings().removeClass("active");
    $this.parent().addClass("active");
  });
  // form reset on new button
  // $("#addnewbtn").on("click", function () {
  //   $("#addform")[0].reset();
  //   $("#categoryid").val("");
  // });

  //  $("#addnewmessagebtn").on("click", function () {

  //   $("#addmessageform")[0].reset();
  //   $("#id").val("");
  // });

}

function get_data() {
  $(document).on("click", '#category_edit', function () {
    var id = $(this).data('id');

    $.ajax({
      url: "ajax.php",
      type: "GET",
      dataType: "json",
      data: { id: id, action: "getcategoryfields" },
      beforeSend: function () {
        $("#overlay").fadeIn();
      },
      success: function (category) {
        if (category) {
          $('#cat_name').val(category.category);
          $('#hassubcategory').prop('checked', category.has_subcategory == 1);
          $('#cat_id').val(category.id);

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

function sub_category() {
  $(document).on("click", '#subcategory_btn', function () {
    var id = $(this).data('id');
        // Retrieve existing array from session storage or initialize it
        var subcategoryArray = [];

        // Add new subcategory ID to the array
        subcategoryArray.push(id);
    
        // Save updated array back to session storage
        sessionStorage.setItem('category_id', JSON.stringify(subcategoryArray));
        console.log(subcategoryArray);
        sessionStorage.setItem('category_count', 0);
    location.href='../sub_category/index.php';
  })
}

function products() {
  $(document).on("click", '#product_btn', function () {
    var id = $(this).data('id');
    sessionStorage.setItem('product_id', id);
    location.href='../products/index.php';
  })
}

function getcategories() {
  var pageno = $("#currentpage").val();
  $.ajax({
    url: "ajax.php",
    type: "GET",
    dataType: "json",
    data: { page: pageno, action: "getcategory" },
    beforeSend: function () {
      $("#overlay").fadeIn();
    },
    success: function (rows) {
      console.log(rows);
      console.log('hiii');

      if (rows.category) {
        var categorylist = "";
        $.each(rows.category, function (index, category) {
          categorylist += getcategoriesrow(index, category, pageno);

        });

        $("#categorytable tbody").html(categorylist);



        let totalcategorys = rows.count;
        let totalpages = Math.ceil(parseInt(totalcategorys) / 15);

        console.log(totalcategorys, totalpages);
        const currentpage = $("#currentpage").val();
        console.log(currentpage);
        pagination(totalpages, currentpage);
        $("#overlay").fadeOut();
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert("Error 124 : " + thrownError);
    },
  });
}




// get player row
function getcategoriesrow(index, category, page) {
  var categoryRow = "";
  if (category) {

    const getbutton = () => {
      if (category.has_subcategory == 0) {
        return `<button type="button" id="product_btn" class="btn btn-secondary mr-2" data-toggle="modal"
          data-target="#userViewModal" data-id="${category.id}" title="products">
          Manage Products
        </button>`
      } else {
        return `<button type="button" id="subcategory_btn" class="btn btn-secondary mr-2" data-toggle="modal"
          data-target="#userViewModal" data-id="${category.id}" title="sub_category">
          Manage Sub Category
        </button>`
      }
    }

    const sl= 15*(page-1)+index+1;

    // const userphoto = player.photo ? player.photo : "default.png";
    categoryRow = `<tr>
            <td data-target="id">${sl}</td>
                <td class="text-center" data-target="cat_name">${category.category}</td>
                <td class="text-center" data-target="parent_category_id"><img width="100" height="100" src="../category_image/${category.image}" ></td>
                <td hidden data-target="parent_category_id">${category.parent_category_id}</td>
            <td id="top">
                      <button type="button" id="category_edit" class="btn btn-warning mr-2"
                          data-toggle="modal" data-target="#userViewModal" data-id="${category.id}" title="Edit">
                          Edit
                      </button>
                    ${getbutton()}
                  </td>
          </tr>`;
  }
  return categoryRow;
}