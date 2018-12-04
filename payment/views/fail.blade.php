
<body onload="func()">
</body>
<script>
    function func (){
        setTimeout(function(){
            var ev = new CustomEvent('fail',{
                detail: {
                    msg: "{{$msg}}"
                }
            })
            document.dispatchEvent(ev)
        },1000)
    }
</script>
