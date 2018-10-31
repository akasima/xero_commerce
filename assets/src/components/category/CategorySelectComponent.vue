<template>
    <select class="parent_select" v-model="selectValue" @change="getChild(selectValue)">
        <option value="">선택</option>
        <option v-for="categoryItem in categoryItems"
                :value="categoryItem['value']">{{ categoryItem['text'] }}</option>
    </select>
</template>

<script>
    export default {
        name: "CategorySelectComponent",
        props: [
            'categoryItems', 'index', 'selected'
        ],
        data() {
            return {
                selectValue : ''
            }
        },
        methods: {
            getChild : function (parentId) {
                this.$emit('selectParent', parentId, this.index);
            }
        },
        mounted () {
            if(this.selected){
                console.log(this.selected)
                this.selectValue = this.selected
            }
        }
    }
</script>

<style scoped>

</style>
