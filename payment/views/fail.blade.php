
<body onload="func()">
</body>
<script>
    (function () {
        if (
            typeof window.CustomEvent === "function" ||
            // In Safari, typeof CustomEvent == 'object' but it otherwise works fine
            this.CustomEvent.toString().indexOf('CustomEventConstructor')>-1
        ) { return; }

        function CustomEvent ( event, params ) {
            params = params || { bubbles: false, cancelable: false, detail: undefined };
            var evt = document.createEvent( 'CustomEvent' );
            evt.initCustomEvent( event, params.bubbles, params.cancelable, params.detail );
            return evt;
        }

        CustomEvent.prototype = window.Event.prototype;

        window.CustomEvent = CustomEvent;
    })();
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
