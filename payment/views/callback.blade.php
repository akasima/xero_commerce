
<body onload="func()">
test
</body>
<script>
    function func (){
        console.log('load!')
        setTimeout(function(){
            var ev = new Event('complete')
            document.dispatchEvent(ev)
        },1000)
    }
</script>
