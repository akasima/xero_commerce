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
            'categoryItems', 'index'
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
        }
    }
</script>

<style scoped>

</style>
