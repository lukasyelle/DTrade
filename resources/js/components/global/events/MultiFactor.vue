<template>
    <el-dialog title="Robinhood Multi-Factor Authentication" :visible.sync="dialogVisible">
        <p>
            {{ message }}
        </p>
        <el-form :model="dialogForm">
            <el-form-item label="MFA Code:">
                <el-input v-model="dialogForm.robinhoodMFA" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item>
                <el-button type="success" @click="submit" >Submit</el-button>
            </el-form-item>
        </el-form>
    </el-dialog>
</template>

<script>
    export default {
        props: {
            'userId': { required: true, type: Number }
        },
        data () {
            return {
                message: '',
                dialogVisible: false,
                dialogForm: {
                    robinhoodMFA: null,
                }
            };
        },
        methods: {
            submit () {
                axios.post(`/api/user/${this.userId}/robinhood/mfa`, { code: this.dialogForm.robinhoodMFA })
                    .then((result) => {
                        this.dialogVisible = false;
                    })
                    .catch((e) => {
                        this.$notify({
                            title: 'Error',
                            message: e.message,
                            type: 'error',
                            duration: 2000
                        })
                    });
            }
        },
        mounted () {
            let a = Echo.private(`user.${ this.userId }`)
                .listen('Robinhood.MultiFactorNecessary', (result) => {
                    this.message = result.message;
                    this.dialogVisible = true;
                })
                .listen('Robinhood.MultiFactorFailed', () => {
                    this.$notify({
                        title: 'Error',
                        message: 'MFA session expired, please log out and try again.',
                        type: 'error',
                        duration: 2000
                    })
                });
        }
    }
</script>

<style scoped>

</style>
