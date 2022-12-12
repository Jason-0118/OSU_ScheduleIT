</main>





<!-- content -->

<script>
    function Menu(self) {
        const list = document.querySelector("ul");
        self.name === 'menu-outline' ? (self.name = "close-outline", list.classList.add("top-[80px]"), list.classList.add("opacity-100")) :
            (self.name = "menu-outline", list.classList.remove("top-[80px]"), list.classList.remove("opacity-100"))
    }


    function Dropdown() {
        const list = document.getElementById("dropdown");
        list.classList.toggle("hidden");
    }
</script>
</body>

</html>