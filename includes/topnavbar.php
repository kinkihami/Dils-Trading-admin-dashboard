<!------top-navbar-start----------->

<div class="top-navbar">
   <div class="xd-topbar">
      <div class="row">
         <div class="align-self-center">
            <div class="xp-menubar">
               <span class="material-icons">menu</span>
            </div>
         </div>
         <div class="order-md-3 align-self-center">

            <div class="xp-breadcrumbbar">
               <h3 class="font-weight-bold" id="page-title">Dashboard</h3>
            </div>
         </div>

      </div>



   </div>
</div>
<!------top-navbar-end----------->


<script>

    document.addEventListener('DOMContentLoaded', () => {
    // Function to update the title and description from session storage
    function updateTitleFromSession() {
        const savedTitle = sessionStorage.getItem('page-title');
        
        if (savedTitle) {
            document.getElementById('page-title').textContent = savedTitle;
            
        }

        const currentPath = window.location.pathname;
        document.querySelectorAll('.sidebar-link').forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.parentElement.classList.add('active');
            } else {
                link.parentElement.classList.remove('active');
            }
        });
    }

    // Function to handle sidebar link clicks
    function handleSidebarLinkClick(event) {
        event.preventDefault(); // Prevent the default link behavior
        const title = this.getAttribute('data-title');
        

        document.getElementById('page-title').textContent = title;
        

        sessionStorage.setItem('page-title', title); // Save the title in session storage
        

        // Update active class
        document.querySelectorAll('.sidebar-link').forEach(link => link.parentElement.classList.remove('active'));
        this.parentElement.classList.add('active');

        // Navigate to the href after updating the title
        setTimeout(() => {
            window.location.href = this.getAttribute('href');
        }, 100); // Adding a small delay to allow the title to update
    }

    // Update the title from session storage on page load
    updateTitleFromSession();

    // Attach event listeners to sidebar links
    document.querySelectorAll('.sidebar-link').forEach(link => {
        link.addEventListener('click', handleSidebarLinkClick);
    });
});


</script>
