<div class="float-right mr-2 mb-3" onclick=showdialog(true)><button type="button"
        class="btn btn-info px-4 pb-1 pt-2"><i class="fa-regular fa-square-plus mr-2"></i> Add</button></div>

<dialog id="adddealer">
    <div class="box" id="adddealerbox">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold" id="exampleModalLabel">Add/Edit Dealer</h5>

            </div>
            <span id="msggg" class="text-center"></span>
            <form id="addform" method="POST">
                <div class="modal-body">
                    <div class="form-group">

                        <label for="dealername" class="col-form-label-sm">Owner:</label>
                        <div class="input-group mb-3">

                            <input type="text" class="form-control" id="owner" name="title" placeholder="Name"
                                required="required">
                        </div>
                    </div>
                    <div class="form-group">
                    <label for="dealername" class="col-form-label-sm">Shop Name:</label>
                        <div class="input-group mb-3">

                            <input type="text" class="form-control" id="shop" name="title" placeholder="Shop"
                                required="required">
                        </div>
                    </div>

                    <div class="form-group">

                        <label for="phone" class="col-form-label-sm">Phone Number:</label>
                        <div class="input-group mb-3">

                        <input type="text" class="form-control" id="phone" name="title" placeholder="Phone"
                        required="required">
                        </div>
                    </div>
                    <div class="form-group">

                        <label for="address" class="col-form-label-sm">Address:</label>
                        <div class="input-group mb-3">

                        <input type="text" class="form-control" id="address" name="title" placeholder="Address"
                        required="required">
                        </div>
                    </div>
                    <div class="form-group">

                        <label for="gst" class="col-form-label-sm">GST No:</label>
                        <div class="input-group mb-3">

                        <input type="text" class="form-control" id="gst" name="title" placeholder="No"
                        required="required">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label-sm">Is Premium:

                        </label>
                        <input type="checkbox" id="premium" name="premium" placeholder="Is Premium" value="1">
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"
                        onclick=showdialog(false)>Close</button>
                    <button type="button" class="btn btn-success" id="adddealerbtn">Submit</button>
                    <input type="hidden" name="action" value="adduser">
                    <input type="hidden" name="id" id="dealerid" value="">
                </div>
            </form>
        </div>

    </div>
</dialog>

<script>

    const dialog1 = document.getElementById('adddealer');
    const box1 = document.getElementById('adddealerbox');

    const showdialog = (show) => {
        $('#msggg').html('');
        if (show) {

            dialog1.showModal();


        } else {

            dialog1.close();
            $('form').trigger('reset');
            $('#dealerid').val("");
        }
    };

    dialog1.addEventListener('click', (e) => !box1.contains(e.target) && showdialog(false));




</script>
<script src="script.js"></script>