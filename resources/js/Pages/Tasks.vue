<template>
    <div class="p-4 max-w-xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">Task List</h1>

        <form @submit.prevent="submitTask" class="mb-4">
            <input v-model="form.title" type="text" placeholder="New task" class="border p-2 w-full mb-2" />
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Add</button>
        </form>

        <ul>
            <li v-for="task in tasks" :key="task.id" class="flex justify-between items-center mb-2">
                <div class="flex items-center">
                    <input type="checkbox" :checked="task.completed" @change="toggleCompletion(task)" />
                    <span :class="{ 'line-through': task.completed }" class="ml-2">{{ task.title }}</span>
                </div>

                <button @click="deleteTask(task)" class="text-red-500 ml-4">Delete</button>
            </li>
        </ul>
    </div>
</template>

<script setup>
import { reactive, onMounted, defineProps } from 'vue'
import { useForm, router } from '@inertiajs/vue3'

const props = defineProps({
    tasks: Array // Define the 'tasks' prop and its type
})

const form = reactive({ title: '' })
const tasks = reactive(props.tasks) // Initialize tasks with the prop

// Add new task
const submitTask = () => {
    router.post(route('tasks.store'), form, {
        onSuccess: () => {
            form.title = '';
            fetchTasks(); // Re-fetch to update the list
        }
    })
}

// Update task completion status
const toggleCompletion = (task) => {
    router.put(route('tasks.update', task.id), { completed: !task.completed }, {
        onSuccess: () => {
            // No need to re-fetch here as we're updating optimistically
        }
    })
    task.completed = !task.completed // Update the task status optimistically
}

// Delete task
const deleteTask = (task) => {
    router.delete(route('tasks.destroy', task.id), {
        onSuccess: () => {
            tasks.splice(tasks.indexOf(task), 1) // Remove from list optimistically
        }
    })
}

// Fetch tasks (for subsequent updates or direct navigation)
const fetchTasks = () => {
    router.get(route('tasks.index'), {}, {
        onSuccess: (response) => {
            tasks.splice(0, tasks.length, ...response.props.tasks)
        },
        onError: (error) => {
            console.error("Error fetching tasks:", error)
        }
    })
}

// Fetch tasks on mount if the prop is empty (direct navigation)
onMounted(() => {
    if (props.tasks.length === 0) {
        fetchTasks();
    }
})
</script>

<style scoped>
.line-through {
    text-decoration: line-through;
}
</style>