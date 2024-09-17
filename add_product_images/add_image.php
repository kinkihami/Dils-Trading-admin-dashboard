<div class="float-right mr-2 mb-3" onclick=showdialog(true)><button type="button"
        class="btn btn-info px-4 pb-1 pt-2"><i class="fa-regular fa-square-plus mr-2"></i> Add</button></div>

<dialog id="addimage">
    <div id="addimagebox">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold" id="exampleModalLabel">Add/Edit Image</h5>

            </div>
            <span id="msggg" class="text-center"></span>
            <form id="addform" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">

                    <label for="recipient-name" class="col-form-label-sm">Product Image:</label>
                        <div class="input-group mb-3">

                            
                            <input type="file" id="bottom" class="form-control p_input" placeholder="" name="image"
                                required="">
                        </div>
                    </div>



                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"
                        onclick=showdialog(false)>Close</button>
                    <button type="button" class="btn btn-success" id="addimagebtn">Submit</button>
                    <input type="hidden" name="action" value="addimage">
                    <input type="hidden" name="id" id="imageid" value="">
                    <input type="hidden" name="productid" id="productid" value="">
                </div>
            </form>
        </div>

    </div>
</dialog>

<script>

    const dialog1 = document.getElementById('addimage');
    const box1 = document.getElementById('addimagebox');


    const showdialog = (show) => {
        $('#msggg').html('');
        if (show) {

            dialog1.showModal();


        } else {

            dialog1.close();
            $('form').trigger('reset');
            $('#imageid').val('');
        }
    };

    dialog1.addEventListener('click', (e) => !box1.contains(e.target) && showdialog(false));



</script>