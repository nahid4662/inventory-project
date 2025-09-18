
<template>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">{{ form.id === 0 ? 'Create Customer' : 'Edit Customer' }}</h5>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="submit">
                            <input id="id" name="id" v-model="form.id" type="hidden" />
                            <div class="mb-3">
                                <label for="name" class="form-label">Customer Name</label>
                                <input id="name" name="name" v-model="form.name" placeholder="Enter customer name" class="form-control" :class="{'is-invalid': form.errors.name}" type="text" autocomplete="off" />
                                <div v-if="form.errors.name" class="invalid-feedback">{{ form.errors.name }}</div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Customer Email</label>
                                <input id="email" name="email" v-model="form.email" placeholder="Enter customer email" class="form-control" :class="{'is-invalid': form.errors.email}" type="email" />
                                <div v-if="form.errors.email" class="invalid-feedback">{{ form.errors.email }}</div>
                            </div>
                            <div class="mb-3">
                                <label for="mobile" class="form-label">Customer Mobile</label>
                                <input id="mobile" name="mobile" v-model="form.mobile" placeholder="Enter customer mobile" class="form-control" :class="{'is-invalid': form.errors.mobile}" type="text" />
                                <div v-if="form.errors.mobile" class="invalid-feedback">{{ form.errors.mobile }}</div>
                            </div>
                            <button type="submit" class="btn btn-success w-100">
                                {{ form.id === 0 ? 'Create' : 'Update' }} Customer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script setup>
import {useForm,router,Link} from '@inertiajs/vue3'

import { usePage } from '@inertiajs/vue3';
import {ref} from "vue";
const page = usePage();

const urlParams=new URLSearchParams(window.location.search)
let id=ref(parseInt(urlParams.get('id')))



const form = useForm({
    name:'',
    email:'',
    mobile:'',
    id:id
})

let URL="/create-customer";
let list=page.props.customer
if(id.value!==0 && list!==null){
    URL="/update-customer";
    // fill the form with existing
    form.name=list.name
    form.email=list.email
    form.mobile=list.mobile
    form.id=list.id
}


function submit(){
    form.post(URL,{
        onSuccess: () => {
            if (page.props.flash.status === true) {
                router.get("/CustomerPage")
            }
            else {
                alert(page.props.flash.message ?? "Something went wrong")
            }
        }
    })
}

</script>
