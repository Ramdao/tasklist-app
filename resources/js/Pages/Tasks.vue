<template>
  <div class="p-4 max-w-6xl mx-auto">
    <img src="images/TopNavigation.png" alt="Top Navigation">
    <img src="images/Sidebar.png" alt="Side Navigation" class="absolute top-20 left-10 h-full ">

    <div class="h-[100px] bg-gray-100 mb-4 flex items-center">
      <h1 class="text-lg font-bold mb-4 font-inter ml-40">Task Management</h1>
    </div>

    <!-- Search and Add Task Button -->
    <input
      type="text"
      placeholder="🔍 Search for tasks by title"
      v-model="searchQuery"
      class="border p-2 w-[307px] mb-4 absolute top-[12rem] left-[11rem]"
    />
    <button
      @click="openModal"
      class="bg-[#BE1E2D] hover:bg-green-400 text-white px-4 py-2 rounded mb-4 absolute top-[12rem] left-[32rem]"
    >
      Add Task +
    </button>

   <div class="absolute top-[12rem] left-[65rem] z-10 ">
  <button @click="toggleFilterDropdown"
    class="hover:bg-green-400 text-black px-4 py-2 rounded mb-1 w-[150px]">
    Sort By
  </button>
  <div v-if="showFilterDropdown" class="bg-white border rounded shadow-md mt-1 w-full z-10">
    <ul class="text-left">
      <button @click="sortTasksBy('title')" class="block px-4 py-2 hover:bg-gray-100 w-full text-left">Sort by Title</button>
      <button @click="sortTasksBy('Priority')" class="block px-4 py-2 hover:bg-gray-100 w-full text-left">Sort by Priority</button>
      <button @click="sortTasksBy('Status')" class="block px-4 py-2 hover:bg-gray-100 w-full text-left">Sort by Status</button>
    </ul>
  </div>
</div>

    <!-- Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white p-6 rounded w-[80%] h-full relative">
        <button @click="closeModal" class="absolute top-2 right-2 text-gray-500 hover:text-black">✖</button>
        <h2 class="text-xl font-semibold mb-4">{{ isEditing ? 'Edit Task' : 'Add New Task' }}</h2>
        <form @submit.prevent="submitTask">
          <input
            v-model="form.title"
            type="text"
            placeholder="Title"
            class="border p-2 w-full mb-2 rounded bg-gray-100"
          />

          <select v-model="form['Assigned By']" class="border p-2 w-full mb-2 rounded bg-gray-100">
            <option value="" disabled selected class="text-gray-400">Select Assigned By</option>
            <option value="Billing Manager">Billing Manager</option>
            <option value="Moderator">Moderator</option>
            <option value="Compliance Manager">Compliance Manager</option>
            <option value="Admin">Admin</option>
          </select>

          <input
            v-model="form['Assigned To']"
            type="text"
            placeholder="Assigned To"
            class="border p-2 w-full mb-2 rounded bg-gray-100"
          />

          <select v-model="form.Priority" class="border p-2 w-full mb-2 rounded bg-gray-100">
            <option value="" disabled selected class="text-gray-400">Select Priority</option>
            <option value="High">High</option>
            <option value="Medium">Medium</option>
            <option value="Low">Low</option>
          </select>

          <select v-model="form.Status" class="border p-2 w-full mb-2 rounded bg-gray-100">
            <option value="" disabled selected class="text-gray-400">Select Status</option>
            <option value="To Do">To Do</option>
            <option value="Pending">Pending</option>
            <option value="In Progress">In Progress</option>
            <option value="In Review">In Review</option>
            <option value="Completed">Completed</option>
          </select>

          <input v-model="form['Due Date']" type="date" class="border p-2 w-full mb-2 rounded bg-gray-100" />
          <button type="submit" class="bg-[#BE1E2D] hover:bg-green-400 text-white px-4 py-2 rounded">
            {{ isEditing ? 'Update Task' : 'Submit' }}
          </button>
        </form>
      </div>
    </div>

    <!-- Task Table -->
    <table class="w-[62.375rem] mt-4 absolute top-[15rem] left-[11rem]">
      <thead class="bg-[#BE1E2D] text-white">
        <tr>
          <th class="px-4 py-2">Title</th>
          <th class="px-2 py-1">Assigned By</th>
          <th class="px-2 py-1">Assigned To</th>
          <th class="px-2 py-1">Priority</th>
          <th class="px-2 py-1">Status</th>
          <th class="px-2 py-1">Due Date</th>
          <th class="px-2 py-1">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="task in filteredTasks"
          :key="task.id"
          class="odd:bg-white even:bg-gray-100 text-center"
        >
          <td class="font-inter px-6 py-4" :class="{ 'line-through': task.completed }">{{ task.title }}</td>
          <td class="font-inter px-2 py-1">{{ task['Assigned By'] }}</td>
          <td class="font-inter px-2 py-1">{{ task['Assigned To'] }}</td>
          <td class="font-inter px-2 py-1 text-center">
            <span
                class="text-white px-2 py-1 rounded text-sm font-semibold"
                :class="{
                'bg-[#BE1E2D]': task.Priority === 'High',
                'bg-[#FFA000]': task.Priority === 'Medium',
                'bg-[#309147]': task.Priority === 'Low'
                }"
            >
                {{ task.Priority }}
            </span>
            </td>

          <td class="py-4 px-6 text-center"
            :class="{
                'text-[#4536F6]': task.Status === 'To Do',
                'text-[#309147]': task.Status === 'Completed',
                'text-[#FFA000]': task.Status === 'In Progress',
                'text-[#FF0909]': task.Status === 'In Review',
                'text-[#FF0909]': task.Status === 'Pending'
                    }">
  {{ task.Status }}
</td>
          <td class="font-inter px-2 py-1">{{ formatDate(task['Due Date']) }}</td>
          <td class="font-inter px-2 py-1 text-center">
            <button @click="editTask(task)" class="text-blue-500 mr-2">Edit</button>
            <button @click="deleteTask(task)" class="text-red-500">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { reactive, onMounted, ref, computed, defineProps } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  tasks: Array
})

const showModal = ref(false)
const isEditing = ref(false)
const editingTaskId = ref(null)

const form = reactive({
  title: '',
  'Assigned By': '',
  'Assigned To': '',
  Priority: '',
  Status: '',
  'Due Date': ''
})

const tasks = reactive(props.tasks)
const searchQuery = ref('')

const filteredTasks = computed(() => {
  let result = tasks.filter((task) =>
    task.title.toLowerCase().includes(searchQuery.value.toLowerCase())
  )

  if (sortKey.value) {
    result = [...result].sort((a, b) => {
      const valA = a[sortKey.value]?.toString().toLowerCase() || ''
      const valB = b[sortKey.value]?.toString().toLowerCase() || ''
      return valA.localeCompare(valB)
    })
  }

  return result
})


const openModal = () => {
  resetForm()
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  isEditing.value = false
  editingTaskId.value = null
}

const submitTask = () => {
  if (isEditing.value) {
    router.put(route('tasks.update', editingTaskId.value), form, {
      onSuccess: () => {
        resetForm()
        fetchTasks()
      }
    })
  } else {
    router.post(route('tasks.store'), form, {
      onSuccess: () => {
        resetForm()
        fetchTasks()
      }
    })
  }
}

const formatDate = (dateStr) => {
  if (!dateStr) return ''
  const date = new Date(dateStr)
  return new Intl.DateTimeFormat('en-GB', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  }).format(date)
}

const editTask = (task) => {
  form.title = task.title
  form['Assigned By'] = task['Assigned By']
  form['Assigned To'] = task['Assigned To']
  form.Priority = task.Priority
  form.Status = task.Status
  form['Due Date'] = task['Due Date']
  editingTaskId.value = task.id
  isEditing.value = true
  showModal.value = true
}

const resetForm = () => {
  form.title = ''
  form['Assigned By'] = ''
  form['Assigned To'] = ''
  form.Priority = ''
  form.Status = ''
  form['Due Date'] = ''
  editingTaskId.value = null
  isEditing.value = false
  showModal.value = false
}

const deleteTask = (task) => {
  router.delete(route('tasks.destroy', task.id), {
    onSuccess: () => {
      tasks.splice(tasks.indexOf(task), 1)
    }
  })
}

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

onMounted(() => {
  if (props.tasks.length === 0) {
    fetchTasks()
  }
})






const sortKey = ref('')
const showFilterDropdown = ref(false)

const toggleFilterDropdown = () => {
  showFilterDropdown.value = !showFilterDropdown.value
}

const sortTasksBy = (key) => {
  sortKey.value = key
  showFilterDropdown.value = false
}

</script>

<style scoped>
.line-through {
  text-decoration: line-through;
}
</style>
