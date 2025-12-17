
// composables/useDeleteConfirmation.js
import axios from 'axios'
import Swal from 'sweetalert2'

export function useDeleteConfirmation() {
    /**
     * Show delete confirmation dialog and handle deletion
     * @param {Object} options - Configuration options
     * @param {string} options.url - API endpoint URL
     * @param {Function} options.onSuccess - Callback after successful deletion
     * @param {string} options.title - Dialog title (default: 'Are you sure?')
     * @param {string} options.text - Dialog text (default: "You won't be able to revert this!")
     * @param {string} options.confirmButtonText - Confirm button text (default: 'Yes, delete it!')
     * @param {string} options.successTitle - Success message title (default: 'Deleted!')
     * @param {Function} options.onError - Callback on error (optional)
     */
    const confirmDelete = async ({
        url,
        onSuccess,
        title = 'Are you sure?',
        text = "You won't be able to revert this!",
        confirmButtonText = 'Yes, delete it!',
        cancelButtonText = 'Cancel',
        successTitle = 'Deleted!',
        onError = null
    }) => {
        const result = await Swal.fire({
            title,
            text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText,
            cancelButtonText,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6'
        })

        if (result.isConfirmed) {
            try {
                const res = await axios.delete(url)

                if (res.data.status === 'success') {
                    await Swal.fire(
                        successTitle,
                        res.data.message,
                        'success'
                    )

                    if (onSuccess) {
                        onSuccess(res.data)
                    }
                } else {
                    throw new Error(res.data.message || 'Deletion failed')
                }
            } catch (error) {
                await Swal.fire(
                    'Error!',
                    error.response?.data?.message || error.message || 'Something went wrong',
                    'error'
                )

                if (onError) {
                    onError(error)
                }
            }
        }
    }

    return {
        confirmDelete
    }
}
