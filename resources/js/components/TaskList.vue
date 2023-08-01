<template>
  <div>
    <!-- Show loading message or spinner while fetching data -->
    <div v-if="isLoading" class="text-center mt-4">
      <i class="fas fa-spinner fa-spin fa-3x"></i>
      <p>Loading...</p>
      <!-- You can also use a spinner here -->
    </div>
    <table v-else class="table">
      <thead>
        <tr>
          <th scope="col">
            <span @click="sortBy('id')" style="cursor: pointer;">
              Task #
              <i :class="['fas', sortKey === 'id' ? (sortDirection === 1 ? 'fa-chevron-down' : 'fa-chevron-up') : 'fa-sort']"></i>
            </span>
          </th>
          <th scope="col">
            <span @click="sortBy('title')" style="cursor: pointer;">
              Title
              <i :class="['fas', sortKey === 'title' ? (sortDirection === 1 ? 'fa-chevron-down' : 'fa-chevron-up') : 'fa-sort']"></i>
            </span>
          </th>
          <th scope="col">
            <span @click="sortBy('description')" style="cursor: pointer;">
              Description
              <i :class="['fas', sortKey === 'description' ? (sortDirection === 1 ? 'fa-chevron-down' : 'fa-chevron-up') : 'fa-sort']"></i>
            </span>
          </th>
          <th scope="col">
            <span @click="sortBy('status')" style="cursor: pointer;">
              Status
              <i :class="['fas', sortKey === 'status' ? (sortDirection === 1 ? 'fa-chevron-down' : 'fa-chevron-up') : 'fa-sort']"></i>
            </span>
          </th>
          <th scope="col">
            <span @click="sortBy('created_at')" style="cursor: pointer;">
              Date Created
              <i :class="['fas', sortKey === 'created_at' ? (sortDirection === 1 ? 'fa-chevron-down' : 'fa-chevron-up') : 'fa-sort']"></i>
            </span>
          </th>
          <th scope="col">Uploaded File</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="task in sortedTasks" :key="task.id">
          <td>
              {{ task.id }}
          </td>
          <td>{{ task.title }}</td>
          <td>{{ task.description }}</td>
          <td>{{ task.status }}</td>
          <td>{{ task.created_at }}</td>
          <td>
            <!-- Show the file name if available -->
            <span v-if="task.file_name">
              {{ task.file_name }}
              <!-- Provide a link to download or view the file -->
              <a :href="task.file_path" target="_blank">Download</a>
            </span>
            <span v-else>No File Uploaded</span>
          </td>
          <td>
            <div class="d-flex justify-content-between align-items-center">
            <div>
            <div v-if="loadingTasks.includes(task.id)">
              <i class="fas fa-spinner fa-spin"></i>
            </div>
            <div v-else>
            <div class="row gap-3">
              <button @click="toggleViewModal(task)" class="p-2 col border btn" :style="{ backgroundColor: buttonColor }">
                <i class="fas fa-eye"></i> 
              </button>
              <button @click="toggleEditModal(task)" class="p-2 col border btn" :style="{ backgroundColor: buttonColor }">
                <i class="fas fa-pencil-alt"></i> 
              </button>
              <button @click="showDeleteModal(task)" type="button" :style="{ backgroundColor: buttonColor }" class="p-2 col border">
                <i
                class="fas fa-trash"
              ></i>
  
              </button>
            </div>
          </div>
          <div class="d-flex align-items-center">
            <!-- ... (other elements if any) ... -->
          </div>
          </div>
        </div>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- New Task Modal -->
    <div v-if="showModal" class="modal">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header text-white">
            <h3 class="modal-title">Confirm Deletion</h3>
            <button type="button" class="close" @click="showModal = false">&times;</button>
          </div>
          <div class="modal-body">
            <p>Are you sure you want to delete this task?</p>
          </div>
          <div class="modal-footer">
            <button @click="deleteTask()" type="button" class="btn btn-danger">Yes</button>
            <button type="button" class="btn btn-secondary" @click="showModal = false">Cancel</button>
          </div>
        </div>
      </div>
    </div>

    <!-- View Task Modal -->
<div v-if="showViewModal" class="modal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header text-white">
        <h3 class="modal-title">View Task</h3>
        <button type="button" class="close" @click="showViewModal = false">&times;</button>
      </div>
      <div class="modal-body">
        <!-- Display the task details in the view modal -->
        <p><strong>Task #:</strong> {{ taskToView.id }}</p>
        <p><strong>Title:</strong> {{ taskToView.title }}</p>
        <p><strong>Description:</strong> {{ taskToView.description }}</p>
        <p><strong>Status:</strong> {{ taskToView.status }}</p>
        <div v-if="taskToView.file_path">
          <p><strong>Attached File:</strong> <a :href="taskToView.file_path" target="_blank">{{ taskToView.file_name }}</a></p>
        </div>
      </div>
      <!-- You can add footer buttons if necessary -->
    </div>
  </div>
</div>

<!-- Edit Task Modal -->
<div v-if="showEditModal && taskToEdit" class="modal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header text-white">
        <h3 class="modal-title">Edit Task</h3>
        <button type="button" class="close" @click="showEditModal = false">&times;</button>
      </div>
      <div class="modal-body">
        <!-- Include the TaskOption component within the edit modal -->
        <TaskOption :isNewTask="false" :task="taskToEdit" @task-updated="onUpdateTask" />
        <div v-if="taskToEdit.file_path">
          <p><strong>Attached File:</strong> <a :href="taskToEdit.file_path" target="_blank">{{ taskToEdit.file_name }}</a></p>
        </div>
      </div>
      <div class="modal-footer">
        <button @click="updateTask()" type="button" class="btn btn-primary">Update Task</button>
        <button type="button" class="btn btn-secondary" @click="showEditModal = false">Cancel</button>
      </div>
    </div>
  </div>
</div>

  </div>
</template>

<script>
import axios from 'axios';
import TaskOption from './TaskOption.vue';

export default {
  components: {
    TaskOption,
  },
  props: {
    tasks: Array,
  },
  data() {
    return {
      isLoading: true,
      showModal: false,
      buttonColor: '#F2AA4CFF',
      taskToDelete: null,
      isDeleting: false,
      loadingTasks: [],
      showViewModal: false, // For the view modal
      showEditModal: false, // For the edit modal
      taskToView: null, // For the view modal
      taskToEdit: null, // For the edit modal
      sortKey: null,
      sortDirection: 1,
      taskBeingEdited: null,
      
    };
  },
  computed: {
    // Create a local copy of the tasks prop to work with
    tasksList: {
      get() {
        return this.tasks;
      },
      set(updatedTasks) {
        // When the local tasksList is updated, emit an event to update the parent component
        this.$emit('update-tasks', updatedTasks);
      }
    },

    sortedTasks() {
      if (!this.sortKey) return this.tasksList;

      // Create a copy of the tasks list to avoid modifying the original data
      const tasks = [...this.tasksList];

      // Sort the tasks based on the sortKey and sortDirection
      tasks.sort((a, b) => {
        const keyA = this.getKeyValue(a, this.sortKey);
        const keyB = this.getKeyValue(b, this.sortKey);

        if (keyA < keyB) return -1 * this.sortDirection;
        if (keyA > keyB) return 1 * this.sortDirection;
        return 0;
      });

      return tasks;
    },
  },

  mounted() {
    console.log('TaskList created'); // Add this line to check if the created() hook is executed
    console.log('Received Tasks:', this.tasks); // Log the received tasks

    // Fetch the tasks data from the server initially
    this.fetchTasks();

    // Listen for the task-created event from the parent component (App.vue)
  },
  
  methods: {
    async showDeleteModal(task) {
      this.taskToDelete = task;
      this.showModal = true;
    },
    async deleteTask() {
      const taskId = this.taskToDelete.id;
      try {
        this.loadingTasks.push(taskId); 
        // Call the delete endpoint using Axios
        await axios.delete(`/api/tasks/${taskId}`);
        // Handle success (e.g., show a success message, update the list, etc.)
        this.tasksList = this.tasksList.filter((task) => task.id !== taskId);
        console.log('Task deleted successfully');
        this.showModal = false;
        // Reload the list or update Vue data as needed

        // Emit a custom event to notify the parent component (App) about the task deletion
        this.$emit('task-deleted', taskId); // Emit the custom event here
        
      } catch (error) {
        // Handle error (e.g., show an error message)
        console.error('Error deleting task:', error);
        this.showModal = false;
      } finally {
        this.loadingTasks = this.loadingTasks.filter((taskId) => taskId !== taskId); // Remove the task ID from the loadingTasks array
      }

    },

    onUpdateTask(updatedTask, taskId) {
      // Update the tasks array with the updated task data
      this.tasksList = this.tasksList.map((task) => (task.id === taskId ? updatedTask : task));
    },

    async updateTask() {
      // Call the update endpoint using Axios
      try {
        await axios.put(`/api/tasks/${this.taskToEdit.id}`, this.taskToEdit);
        console.log('Task updated successfully');

        // Update the tasks array with the edited task data
        this.tasksList = this.tasksList.map((task) =>
          task.id === this.taskToEdit.id ? this.taskToEdit : task
        );

        // Close the modal
        this.showEditModal = false;
      } catch (error) {
        console.error('Error updating task:', error);
      }
    },

    async onTaskCreated(newTask) {
      try {
        // Add the new task to the local tasks array
        this.tasks.push(newTask);

        // Log the API response in the console
        console.log('API Response for new task:', newTask);
      } catch (error) {
        console.error('Error creating task:', error);
      }
    },
    
    async fetchTasks() {
      try {
        console.log('Fetching tasks...');
        const response = await axios.get('/api/tasks');
        console.log('API Response:', response.data);
        this.tasksList = response.data;
        this.isLoading = false;
      } catch (error) {
        console.error(error);
        this.isLoading = false; // Set isLoading to false even if there's an error
      }
    },
        showDeleteModal(task) {
        this.taskToDelete = task;
        this.showModal = true;
      },
      toggleViewModal(task) {
        this.taskToView = task;
        this.showViewModal = true;
      },
      toggleEditModal(task) {
          // Create a deep copy of the task being edited and store it in taskBeingEdited
        this.taskToEdit = { ...task };
        // Show the edit modal
        this.showEditModal = true;
      },

      getKeyValue(obj, key) {
      const keys = key.split('.');
      let value = obj;

      for (const k of keys) {
        value = value[k];
      }

      return value;
    },

    // Method to trigger sorting
    sortBy(key) {
      if (this.sortKey === key) {
        // Toggle the sort direction if the same key is clicked
        this.sortDirection *= -1;
      } else {
        // Set the new sort key and default to ascending direction
        this.sortKey = key;
        this.sortDirection = 1;
      }
    },
  },
  created() {
    console.log('Tasks:', this.tasks); // Add this line to check the tasks data
  }

  
};
</script>

<style>
/* Style the modal */

:root {
--primary-color: #101820FF;
--secondary-color: #F2AA4CFF;
}

.modal {
position: fixed;
top: 0;
left: 0;
width: 100%;
height: 100%;
background-color: rgba(0, 0, 0, 0.5);
display: flex;
justify-content: center;
align-items: center;
}

.modal-dialog {
max-width: 400px;
width: 90%;
}

/* Optional - Modify the modal header background color and text color */
.modal-header {
background-color: var(--secondary-color);
color: #fff;
}

/* Optional - Center the modal body text */
.modal-body {
text-align: center;
}

/* Optional - Add some spacing between buttons in the modal footer */
.modal-footer button {
background-color: var(--secondary-color);
margin-right: 10px;
border-style: none;
}
</style>
