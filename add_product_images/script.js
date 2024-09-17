$(document).ready(function () {
  add_data();
  get_data();
  delete_data();
  getimages();
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


function add_data() {
  $(document).on("click", '#addimagebtn', function (event) {
    event.preventDefault();
    var image=$("#bottom").val();
    var id=sessionStorage.getItem("variant_id");
    $("#productid").val(id);


    if (image == "") {
      $('#msggg').html("<p class='alert alert-danger'>Please fill all the fields</p>");
    } else {
      var form_data = new FormData(document.getElementById("addform"));
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
            getimages();
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
    getimages();
    $this.parent().siblings().removeClass("active");
    $this.parent().addClass("active");
  });
  // form reset on new button
  // $("#addnewbtn").on("click", function () {
  //   $("#addform")[0].reset();
  //   $("#imageid").val("");
  // });

  //  $("#addnewmessagebtn").on("click", function () {

  //   $("#addmessageform")[0].reset();
  //   $("#id").val("");
  // });

}

function get_data() {
  $(document).on("click", '#image_edit', function () {
    var id = $(this).data('id');

    $.ajax({
      url: "ajax.php",
      type: "GET",
      dataType: "json",
      data: { id: id, action: "getimagefields" },
      beforeSend: function () {
        $("#overlay").fadeIn();
      },
      success: function (image) {
        if (image) {
          $('#imageid').val(image.id);
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
  $(document).on("click", '#image_delete', function () {

    var a = confirm('Do you really want to delete this?');

    if (a) {
      var id = $(this).data('id');
      $.ajax({
        url: "ajax.php",
        type: "POST",
        dataType: "json",
        data: { id: id, action: "deleteimage" },
        beforeSend: function () {
          $("#overlay").fadeIn();
        },
        success: function (res) {
          console.log(res);
          if (res.deleted == 1) {
            $(".message")
              .html("image has been deleted successfully!")
              .fadeIn()
              .delay(3000)
              .fadeOut();
            getimages();
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

function getimages() {
  var pageno = $("#currentpage").val();
  var id=sessionStorage.getItem('variant_id');

  console.log('inside');
  $.ajax({
    url: "ajax.php",
    type: "GET",
    dataType: "json",
    data: { page: pageno, id: id, action: "getimage" },
    beforeSend: function () {
      $("#overlay").fadeIn();
    },
    success: function (rows) {
      console.log(rows);
      console.log('hiii');

      if (rows.image) {
        var imagelist = "";
        $.each(rows.image, function (index, image) {
          imagelist += getimagesrow(index, image, pageno);

        });

        $("#imagetable tbody").html(imagelist);



        let totalimages = rows.count;
        let totalpages = Math.ceil(parseInt(totalimages) / 15);

        console.log(totalimages, totalpages);
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
function getimagesrow(index, image, page) {
  var imageRow = "";
  if (image) {

    const sl= 15*(page-1)+index+1;

    // const userphoto = player.photo ? player.photo : "default.png";
    imageRow = `<tr>
           <td data-target="id">${sl}</td>
            <td class="text-center" data-target="parent_category_id"><img width="100" height="100" src="../product_image/${image.image}" ></td>
            <td>
                      <button type="button" id="image_edit" class="btn btn-outline-warning mr-2"
                          data-toggle="modal" data-target="#userViewModal" data-id="${image.id}" title="Edit">
                          Edit
                      </button>
                      <button type="button" id="image_delete" class="btn btn-outline-danger mr-2"
                          data-toggle="modal" data-target="#userViewModal" data-id="${image.id}" title="delete">
                          Delete
                      </button>
                  </td>
          </tr>`;
  }
  return imageRow;
}