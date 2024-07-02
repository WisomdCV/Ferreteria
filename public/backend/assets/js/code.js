$(function(){
    $(document).on('click','#delete',function(e){
        e.preventDefault();
        var link = $(this).attr("href");


                Swal.fire({
                    title: 'Estas seguro?',
                    text: "Quieres eliminar los datos?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, eliminarlo!'
                }).then((result) => {
                    if (result.isConfirmed) {
                    window.location.href = link
                    Swal.fire(
                        'Eliminado!',
                        'Los datos han sido eliminados.',
                        'success'
                    )
                    }
                }) 


    });

});
