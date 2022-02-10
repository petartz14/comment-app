<template>
    <div>
        <div
            v-for="(post, i) in posts"
            :key="i"
            class="bg-white rounded-lg border border-gray-200"
        >
            <div class="p-5">
                <div class="font-sans font-medium text-lg">
                    {{ post.title }}
                </div>
                <div class="my-3 italic font-light">{{ post.content }}</div>
            </div>
            <div class="border border-gray-200 border-b" />
            <form @submit.prevent="submit">
                <div class="flex items-center p-5">
                    <div
                        class="md:flex md:space-x-1 space-y-1 md:space-y-0 w-full mr-3"
                    >
                        <input
                            type="text"
                            v-model="form.name"
                            placeholder="Type name..."
                            class="py-2 px-4 w-full md:w-auto rounded-md border border-gray-200 focus:outline-gray-400 placeholder:italic placeholder:text-sm"
                        />

                        <input
                            type="text"
                            v-model="form.comment"
                            placeholder="Type comment..."
                            class="py-2 px-4 w-full rounded-md border border-gray-200 focus:outline-gray-400 placeholder:italic placeholder:text-sm"
                        />
                    </div>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="h-10 w-11 flex bg-blue-800 rounded-md ml-auto"
                    >
                        <paper-airplane-icon
                            class="w-5 h-5 text-white m-auto"
                        ></paper-airplane-icon>
                    </button>
                </div>
            </form>

            <div class="mx-5 mb-5">
                <div v-for="(comment, a) in $attrs.comments" :key="a">
                    <div class="bg-gray-200 rounded-md p-3 shadow">
                        <div class="text-sm font-medium">
                            {{ comment.name }}
                        </div>
                        <p class="text-xs italic">
                            {{ comment.comment }}
                        </p>
                    </div>
                    <div class="mb-4 ml-2">
                        <button
                            @click="toggleReply(comment.id)"
                            class="cursor-pointer text-blue-900 hover:underline text-xs"
                        >
                            Reply
                        </button>
                    </div>
                    <div class="pb-5" v-if="selectReply === comment.id">
                        <c-reply
                            class="ml-auto w-full"
                            :paddingLeft="1"
                            :commentId="comment.id"
                            :comments="comment.replies"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { PaperAirplaneIcon } from "@heroicons/vue/solid";
import { reactive, ref } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";
import CReply from "../../Components/Reply.vue";

export default {
    components: { PaperAirplaneIcon, CReply },

    setup() {
        let selectReply = ref(null);

        let form = useForm({
            name: "",
            comment: "",
        });

        const posts = [
            {
                id: 1,
                title: "What is Lorem Ipsum?",
                content:
                    "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
            },
        ];

        const toggleReply = (id) => {
            if (id === selectReply.value) {
                selectReply.value = null;
            } else {
                selectReply.value = id;
            }
        };

        const submit = () => {
            form.post("/comments", {
                preserveScroll: true,
                onSuccess: () => form.reset(),
            });
        };

        return {
            form,
            submit,
            posts,
            toggleReply,
            selectReply,
        };
    },
};
</script>
