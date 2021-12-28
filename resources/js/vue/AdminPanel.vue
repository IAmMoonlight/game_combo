<template>
    <div class="admin_section">
        <list-users />
        <score-manipulate />
    </div>
</template>

<script>
import ListUsers from "./adminParts/ListUsers";
import ScoreManipulate from "./adminParts/ScoreManipulate";
export default {
    name: "AdminPanel",
    components: {ScoreManipulate, ListUsers},
    data(){
        return {
            message: 'hello'
        }
    }
}
</script>

<style scoped>

</style>
