<template>
  <div>
  
    <div v-if="isLoading" class="text-center mt-4">
      <i class="fas fa-spinner fa-spin fa-3x"></i>
      <p>Loading...</p>
     
    </div>
    
    <div v-else class="container-fluid mt-4">
    <div class="table-responsive">
    <table class="table">
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
          <td>{{ formatDate(task.created_at) }}</td>
          <td>
            
            <span v-if="task.file_name">
              {{ task.file_name }}
              <div class="file-link">
              
              <a :href="task.file_path" target="_blank" class="download-link"><i class="fas fa-download"></i></a>
              </div>
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
              <button @click="toggleViewModal(task)" class="list-button p-2 col border btn">
                <i class="fas fa-eye"></i> 
              </button>
              <button @click="toggleEditModal(task)" class="list-button p-2 col border btn">
                <i class="fas fa-pencil-alt"></i> 
              </button>
              <button @click="showDeleteModal(task)" type="button" class="list-button p-2 col border">
                <i
                class="fas fa-trash"
              ></i>
  
              </button>
            </div>
          </div>
          <div class="d-flex align-items-center">
          </div>
          </div>
        </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  </div>

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


<div v-if="showViewModal" class="modal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header text-white">
        <h3 class="modal-title">{{ taskToView.title }}</h3>
        <button type="button" class="close" @click="showViewModal = false">&times;</button>
      </div>
      <div class="modal-body">
        <div class="modal-task-detail">
        <p><strong>Description:</strong> {{ taskToView.description }}</p>
        <p><strong>Status:</strong> {{ taskToView.status }}</p>
        <div v-if="taskToView.file_path">
          <p><strong>Attached File:</strong> <a :href="taskToView.file_path" target="_blank">{{ taskToView.file_name }}</a></p>
        </div>
      </div>
      </div>
    </div>
  </div>
</div>

<div v-if="showEditModal && taskToEdit" class="modal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header text-white">
        <h3 class="modal-title">Edit Task</h3>
        <button type="button" class="close" @click="showEditModal = false">&times;</button>
      </div>
      <div class="modal-body">
        <form @submit.prevent="updateTask">
          <div class="mb-3">
            <label for="title" class="form-label">Title:</label>
            <input class="form-control" type="text" id="title" v-model="taskToEdit.title" required />
          </div>
          <div class="mb-3">
            <label for="description" class="form-label">Description:</label>
            <textarea class="form-control" id="description" v-model="taskToEdit.description"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Status:</label>
            <div class="form-check form-check-inline">
              <input
                class="form-check-input"
                type="radio"
                name="status"
                id="statusPending"
                value="Pending"
                v-model="taskToEdit.status"
              />
              <label class="form-check-label" for="statusPending">Pending</label>
            </div>
            <div class="form-check form-check-inline">
              <input
                class="form-check-input"
                type="radio"
                name="status"
                id="statusOngoing"
                value="Ongoing"
                v-model="taskToEdit.status"
              />
              <label class="form-check-label" for="statusOngoing">Ongoing</label>
            </div>
            <div class="form-check form-check-inline">
              <input
                class="form-check-input"
                type="radio"
                name="status"
                id="statusCompleted"
                value="Completed"
                v-model="taskToEdit.status"
              />
              <label class="form-check-label" for="statusCompleted">Completed</label>
            </div>
          </div>
          <div class="mb-3">
            <label for="file" class="form-label">File:</label>
            <input type="file" id="file" @change="onFileChange" accept=".pdf" />
            <div v-if="taskToEdit.file_path">
              <p><strong>Attached File:</strong> <a :href="taskToEdit.file_path" target="_blank">{{ taskToEdit.file_name }}</a></p>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Update Task</button>
            <button type="button" class="btn btn-secondary" @click="showEditModal = false">Cancel</button>
          </div>
        </form>
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
    
    tasksList: {
      get() {
        return this.tasks;
      },
      set(updatedTasks) {
        
        this.$emit('update-tasks', updatedTasks);
      }
    },

    sortedTasks() {
      if (!this.sortKey) return this.tasksList;

      
      const tasks = [...this.tasksList];

      
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
    console.log('TaskList created'); 
    console.log('Received Tasks:', this.tasks); 
    this.fetchTasks();
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
        
        await axios.delete(`/api/tasks/${taskId}`);
        
        this.tasksList = this.tasksList.filter((task) => task.id !== taskId);
        console.log('Task deleted successfully');
        this.showModal = false;
       
        this.$emit('task-deleted', taskId); 
        
      } catch (error) {
        
        console.error('Error deleting task:', error);
        this.showModal = false;
      } finally {
        this.loadingTasks = this.loadingTasks.filter((taskId) => taskId !== taskId);
      }

    },

    onUpdateTask(updatedTask, taskId) {
      this.tasksList = this.tasksList.map((task) => (task.id === taskId ? updatedTask : task));
    },

    async updateTask() {
      try {
        await axios.put(`/api/tasks/${this.taskToEdit.id}`, this.taskToEdit);
        console.log('Task updated successfully');

        this.tasksList = this.tasksList.map((task) =>
          task.id === this.taskToEdit.id ? this.taskToEdit : task
        );


        this.showEditModal = false;
      } catch (error) {
        console.error('Error updating task:', error);
      }
    },

    async onTaskCreated(newTask) {
      try {
        this.tasks.push(newTask);
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
        this.isLoading = false; 
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
        this.taskToEdit = { ...task };
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

    sortBy(key) {
      if (this.sortKey === key) {
        this.sortDirection *= -1;
      } else {
        this.sortKey = key;
        this.sortDirection = 1;
      }
    },

    formatDate(dateString) {
    const options = {
      timeZone: 'Asia/Manila',
      year: 'numeric',
      month: 'short',
      day: 'numeric',
      hour: 'numeric',
      minute: 'numeric',
    };
    const formattedDate = new Date(dateString).toLocaleString(undefined, options);
    return formattedDate;
  },
  },
  created() {
    console.log('Tasks:', this.tasks); 
  }

  
};
</script>

<style>

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

.modal-header {
background-color: var(--secondary-color);
color: #fff;
}


.modal-body {
text-align: center;
}


.modal-footer button {
background-color: var(--secondary-color);
margin-right: 10px;
border-style: none;
}

.list-button{
  background: #fff;
  transition: color 0.3s ease;
}
.list-button:hover{
  background: var(--secondary-color);
}

.modal-task-detail {
  text-align: left;

}

.modal-input {
  width: 100%;
  padding: 0.5rem;
  margin-bottom: 1rem;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.download-link i{
  color: var(--secondary-color);
}

@media (max-width: 767px) {
  .table thead {
    display: none;
  }

  .table tbody tr {
    display: block; 
    border: 1px solid #ccc;
    margin-bottom: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1), 0 4px 10px rgba(0, 0, 0, 0.1);
  }

  .table tbody td {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 8px;
  }

  .table tbody td:nth-child(1) {
    display: none;
  }

  .table tbody td:nth-child(2) {
    order: 1;
    width: 100%;
  }

  .table tbody td:nth-child(3) {
    order: 2;
    width: 100%;
  }

  .table tbody td:nth-child(4) {
    display: none;
  }

  .table tbody td:nth-child(5) {
    display: none;
  }

  .table tbody td:nth-child(6) {
    display: none;
  }

  .table tbody td:nth-child(7) {
    order: 2;
    width: 100%;
  }
}
</style>
