<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfilePictureForm from './Partials/UpdateProfilePicture.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import FormContainer from '@/Components/Forms/FormContainer.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout disableSidebar="true">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Profile</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <FormContainer>
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </FormContainer>

                <FormContainer>
                    <UpdatePasswordForm class="max-w-xl" />
                </FormContainer>

                <FormContainer>
                    <UpdateProfilePictureForm class="max-w-xl" />
                </FormContainer>

                <FormContainer>
                    <DeleteUserForm class="max-w-xl" />
                </FormContainer>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
