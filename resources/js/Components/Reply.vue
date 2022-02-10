<template>
    <div :style="paddingL" class="pt-3">
        <div>
            <div v-for="(comment, i) in comments" :key="i">
                <div class="flex">
                    <div class="relative">
                        <div
                            class="my-0 border-b-2 border-l-2 border-gray-400 w-5 mr-2 rounded-bl-lg h-8"
                        ></div>
                        <div
                            v-if="i !== comments.length - 1"
                            class="absolute h-full my-0 border-l-2 border-gray-400 w-5 mr-2 top-0"
                        ></div>
                    </div>
                    <div class="bg-gray-200 rounded-md p-3 shadow w-full">
                        <div class="text-sm font-medium">
                            {{ comment.name }}
                        </div>
                        <p class="text-xs italic">
                            {{ comment.comment }}
                        </p>
                    </div>
                </div>
                <div
                    class="pb-2 pl-10"
                    :class="{
                        'border-l-2 border-gray-400': i !== comments.length - 1,
                    }"
                >
                    <button
                        v-if="!unreply"
                        @click="toggleReply(comment.id)"
                        class="cursor-pointer text-blue-900 hover:underline text-xs"
                    >
                        Reply
                    </button>
                </div>
                <div
                    class="pb-5"
                    :class="{
                        'border-l-2 border-gray-400': i !== comments.length - 1,
                    }"
                    v-if="selectReply === comment.id"
                >
                    <c-reply
                        :paddingLeft="paddingLeft + 2"
                        :commentId="comment.id"
                        :comments="comment.replies"
                        unreply
                    />
                </div>
            </div>
        </div>

        <form @submit.prevent="save">
            <div class="flex items-center">
                <div
                    class="md:flex md:space-x-1 space-y-1 md:space-y-0 w-full mr-3"
                >
                    <input
                        type="text"
                        v-model="form.name"
                        placeholder="Type name..."
                        class="py-1 px-2 w-full md:w-auto rounded-md border border-gray-200 focus:outline-gray-400 placeholder:italic placeholder:text-sm"
                    />

                    <input
                        type="text"
                        v-model="form.comment"
                        placeholder="Type comment..."
                        class="py-1 px-2 w-full rounded-md border border-gray-200 focus:outline-gray-400 placeholder:italic placeholder:text-sm"
                    />
                </div>
                <button
                    type="submit"
                    class="h-8 w-9 flex bg-white border border-gray-300 rounded-md ml-auto"
                >
                    <reply-icon class="w-5 h-5 text-gray-800 m-auto" />
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import { ReplyIcon } from "@heroicons/vue/solid";
import { Inertia } from "@inertiajs/inertia";
import { reactive, ref, computed } from "vue";

export default {
    components: { ReplyIcon },
    props: {
        comments: {
            type: Array,
            default: () => [],
        },
        paddingLeft: {
            type: String,
            default: null,
        },
        commentId: {
            type: Number,
            default: null,
        },
        unreply: {
            type: Boolean,
            default: false,
        },
    },
    setup(props) {
        let selectReply = ref(null);

        const initialState = {
            name: "",
            comment: "",
            comment_id: props.commentId,
        };

        let form = reactive({ ...initialState });

        const toggleReply = (id) => {
            if (id === selectReply.value) {
                selectReply.value = null;
            } else {
                selectReply.value = id;
            }
        };

        const paddingL = computed(() => {
            return { "margin-left": props.paddingLeft + "rem" };
        });

        const resetForm = () => {
            Object.assign(form, initialState);
        };

        const save = () => {
            Inertia.post("/comments", form);

            resetForm();
        };

        return { selectReply, form, toggleReply, save, paddingL, resetForm };
    },
};
</script>
