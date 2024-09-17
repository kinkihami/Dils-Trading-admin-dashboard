$(document).ready(function () {
  add_data();
  get_data();
  delete_data();
  getunits();
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
  $(document).on("click", '#addunitbtn', function (event) {
    event.preventDefault();

    var alertmsg =
      $("#unitid").val().length > 0
        ? "unit has been updated Successfully!"
        : "New unit has been added Successfully!";

        var unit = $('#unit').val();
        var id=$('#unitid').val();

    console.log(id);

    if (unit == "") {
      $('#msggg').html("<p class='alert alert-danger'>Please fill all the fields</p>");
    } else {
      $.ajax({
        url: "ajax.php",
        method: "POST",
        dataType: "json",
        data: { unit: unit, id: id,action: "addunit" },
        success: function (response) {
          $('#msggg').html(response);
          console.log(response);
          if (response.success) {
            showdialog(false);
            getunits();
            $("#overlay").fadeOut();
          }
          console.log('success');
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert("Error : " + thrownError);
          console.error('AJAX Error:', xhr.status, thrownError);
          console.error('Response Text:', xhr.responseText);
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
    getunits();
    $this.parent().siblings().removeClass("active");
    $this.parent().addClass("active");
  });
}

function get_data() {
  $(document).on("click", '#unit_edit', function () {
    var id = $(this).data('id');
   
    $.ajax({
      url: "ajax.php",
      type: "GET",
      dataType: "json",
      data: { id: id, action: "getunitfields" },
      beforeSend: function () {
        $("#overlay").fadeIn();
      },
      success: function (unit) {
        if (unit) {
          $('#unit').val(unit.unit);
          $('#unitid').val(unit.id);
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
  $(document).on("click", '#unit_delete', function () {

    var a = confirm('Do you really want to delete this?');

    if (a) {
      var id = $(this).data('id');
      $.ajax({
        url: "ajax.php",
        type: "POST",
        dataType: "json",
        data: { id: id, action: "deleteunit" },
        beforeSend: function () {
          $("#overlay").fadeIn();
        },
        success: function (res) {
          console.log(res);
          if (res.deleted == 1) {
            $(".message")
              .html("unit has been deleted successfully!")
              .fadeIn()
              .delay(3000)
              .fadeOut();
            getunits();
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

function getunits() {
  var pageno = $("#currentpage").val();

  console.log('inside');
  $.ajax({
    url: "ajax.php",
    type: "GET",
    dataType: "json",
    data: {  page: pageno, action: "getunit" },
    beforeSend: function () {
      $("#overlay").fadeIn();
    },
    success: function (rows) {
      console.log(rows);
      console.log('hiii');

      if (rows.unit) {
        var unitlist = "";
        $.each(rows.unit, function (index, unit) {
          unitlist += getunitsrow(index, unit, pageno);

        });

        $("#unittable tbody").html(unitlist);



        let totalunits = rows.count;
        let totalpages = Math.ceil(parseInt(totalunits) / 15);

        console.log(totalunits, totalpages);
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


function getunitsrow(index, unit, page) {
  var unitRow = "";
  if (unit) {
  
  const sl= 15*(page-1)+index+1;
  
    unitRow = `<tr>
         <td data-target="id">${sl}</td>
          <td class="text-center">${unit.unit}</td>
          <td>
                    <button type="button" id="unit_edit" class="btn btn-warning mr-2"
                        data-toggle="modal" data-target="#userViewModal" data-id="${unit.id}" title="Edit">
                        Edit
                    </button>
                    <button type="button" id="unit_delete" class="btn btn-danger mr-2"
                        data-toggle="modal" data-target="#userViewModal" data-id="${unit.id}" title="delete">
                        Delete
                    </button>
                </td>
        </tr>`;
  }
  return unitRow;
}


  