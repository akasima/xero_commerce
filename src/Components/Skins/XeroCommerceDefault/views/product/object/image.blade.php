{{\App\Facades\XeFrontend::js('https://cdn.jsdelivr.net/npm/vue/dist/vue.min.js')->load()}}
<div class="box-slide" id="image">
    <span class="thumbnail" :style="{'background-image':'url('+mainImg+')'}"></span>
    <div class="box_arrow reset-button">
        <button type="button" class="btn-arrow btn-prev reset-button" @click="beforeMainImage"><i class="xi-angle-left"></i><span class="blind">왼쪽</span></button>
        <button type="button" class="btn-arrow btn-next reset-button" @click="afterMainImage"><i class="xi-angle-right"></i><span class="blind">오른쪽</span></button>
    </div>
    <ul class="list-img reset-list">
        <li v-for="(image, key) in images" class="item-img">
            <a  @click.prevent="changeMainImage(key)" href="#" role="button" :style="{'background-image':'url('+image+')'}"></a>
        </li>
    </ul>
</div>
<script>
    $(function(){
        var image=new Vue({
            el: '#image',
            computed: {
                mainImg: function () {
                    return this.images[this.mainImageKey]
                }
            },
            data: function () {
                return {
                    mainImageKey: 0,
                    images: {!! json_encode($images) !!}
                }
            },
            methods:{
                changeMainImage: function (key) {
                    this.mainImageKey = key
                },
                beforeMainImage: function () {
                    if (this.mainImageKey > 0) {
                        this.mainImageKey--
                    } else {
                        this.mainImageKey = this.images.length - 1
                    }
                },
                afterMainImage: function () {
                    if (this.mainImageKey < this.images.length - 1) {
                        this.mainImageKey++
                    } else {
                        this.mainImageKey = 0
                    }
                },
            }
        })
    });
</script>
