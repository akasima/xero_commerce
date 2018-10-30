
<body onload="func()">
test
</body>
<script>
    function func (){
        console.log('load!')
        setTimeout(()=>{
            var ev = new Event('complete')
            document.dispatchEvent(ev)
        },1000)
    }
</script>
