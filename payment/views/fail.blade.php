
<body onload="func()">
</body>
<script>
    function func (){
        console.log('load!')
        setTimeout(()=>{
            var ev = new CustomEvent('fail',{
                msg: "{{$msg}}"
            })
            document.dispatchEvent(ev)
        },1000)
    }
</script>
