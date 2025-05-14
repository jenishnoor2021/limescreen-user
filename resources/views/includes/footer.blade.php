<!-- JAVASCRIPT -->
<script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
<script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/dashboard.init.js') }}"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>

<!-- form validation -->
{{-- <script src="https://code.jquery.com/jquery-1.10.2.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'csrftoken': '{{ csrf_token() }}'
        }
    });
</script>

<!-- read more button in database -->
<script>
    $(document).ready(function() {
        function AddReadMore() {
            var carLmt = 30;
            var readMoreTxt = " ...read more";
            var readLessTxt = " read less";

            $(".add-read-more").each(function() {
                var content = $(this).text().trim();

                // Skip if already processed
                if ($(this).find(".first-section").length || content.length <= carLmt)
                    return;

                var firstSet = content.substring(0, carLmt);
                var secdHalf = content.substring(carLmt);

                var html = firstSet + "<span class='second-section'>" + secdHalf +
                    "</span><span class='read-more' title='Click to Show More'><u>" + readMoreTxt +
                    "</span><span class='read-less' title='Click to Show Less'><u>" + readLessTxt + "</u></span>";

                $(this).html(html);
            });

            // Toggle content
            $(document).on("click", ".read-more,.read-less", function() {
                $(this).closest(".add-read-more").toggleClass("show-less-content show-more-content");
            });
        }

        AddReadMore();
    });
</script>
<!-- read more button end in database -->



<script>
    function formatAadharInput(input) {
        // Remove any non-numeric characters
        input.value = input.value.replace(/\D/g, '');

        // Limit the input length to 12 characters
        if (input.value.length > 12) {
            input.value = input.value.slice(0, 12);
        }

        // Format the input with spaces after every 4 digits
        input.value = input.value.replace(/(\d{4})/g, '$1 ').trim();
    }
</script>

<!-- Required datatable js -->
<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<!-- Buttons examples -->
<script src="{{ asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/libs/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>

<!-- Responsive examples -->
<script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

<!-- Datatable init js -->
<script src="{{ asset('assets/js/pages/datatables.init.js') }}"></script>

<script>
    $(document).ready(function() {});
</script>

@yield('script')

</body>

</html>