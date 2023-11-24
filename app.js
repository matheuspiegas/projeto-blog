links = document.querySelectorAll(".apagar");

let arrayLinks = Array.from(links);
arrayLinks.map((link) => {
  link.addEventListener("click", (e) => {
    e.preventDefault();
    let href = link.href;
    let idApagar = href.split("=")[1];
    Swal.fire({
      title: "Você tem certeza que deseja apagar esse post?",
      text: "",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Apagar",
      cancelButtonText: "Cancelar"
    }).then((result) => {
      if (result.isConfirmed) {
        fetch("apagar.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ id: idApagar, confirmado: true }),
        })
          .then((res) => res.json())
          .then((data) => {
            console.log(data);
            if (data.apagado === true) {
              Swal.fire({
                title: "Apagado!",
                text: "Seu post foi apagado com sucesso.",
                icon: "success",
                confirmButtonColor: "#3085d6"
              });
              let btnConfirmDelte = document.querySelector(".swal2-confirm");
              btnConfirmDelte.addEventListener("click", () => {
                location.reload();
              });
            }
          });
      }
    });
  });
});
