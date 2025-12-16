$(document).ready(function () {

    // Xóa danh mục
    $(document).on("click", ".btn-delete-category", function (e) {
        e.preventDefault();
        let button = $(this);
        let categoryId = button.data("id");
        let row = button.closest("tr");

        if (confirm("Bạn có chắc chắn muốn xóa danh mục này?")) {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            $.ajax({
                url: "categories/delete",
                type: "POST",
                data: {
                    category_id: categoryId,
                },
                success: function (response) {
                    if (response.status) {
                        toastr.success(response.message);
                        row.fadeOut(300, function () {
                            $(this).remove();
                        });
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function (xhr, status, error) {
                    alert("Có lỗi xảy ra ! Vui lòng thử lại. " + error);
                },
            });
        }
    });

    // Mở modal sửa danh mục
    $(document).on("click", ".btn-edit-category", function (e) {
        e.preventDefault();
        let categoryId = $(this).data("id");
        let categoryName = $(this).data("name");
        let categorySlug = $(this).data("slug");
        let categoryDesc = $(this).data("description");
        let categoryImage = $(this).data("image");

        // Điền dữ liệu vào form
        $("#edit_category_id").val(categoryId);
        $("#edit_name").val(categoryName);
        $("#edit_slug").val(categorySlug);
        $("#edit_description").val(categoryDesc);

        // Set action URL cho form
        let formAction = $("#editCategoryForm").attr("action");
        $("#editCategoryForm").attr("action", formAction.replace(':id', categoryId));

        // Hiển thị hình ảnh hiện tại
        if (categoryImage) {
            $("#current_image").attr("src", "/storage" + categoryImage);
            $("#current_image_container").removeClass("d-none");
        } else {
            $("#current_image_container").addClass("d-none");
        }

        // Ẩn preview hình mới
        $("#new_image_preview_container").addClass("d-none");

        // Mở modal
        $("#editCategoryModal").modal("show");
    });
});

