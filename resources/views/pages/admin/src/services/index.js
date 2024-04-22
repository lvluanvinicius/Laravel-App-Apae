import axios from 'axios';

export const service = axios.create({
  headers: {
    'Content-Type': 'application/json',
  },
  baseURL: `${import.meta.env.VITE_APP_URL}/admin/`,
});

/**
 * Recupera as categorias para o editor de post.
 * @author Luan Santos  <lvluansantos@gmail.com>
 */
export async function getCategories() {
  return await service
    .get('/news/categories/data')
    .then((response) => {
      return response.data;
    })
    .catch((error) => {
      alert(error.message);
    });
}

/**
 * Salva um novo post.
 * @author Luan Santos  <lvluansantos@gmail.com>
 */
export async function savePost(data) {
  return await service
    .post('/news/post-save', data)
    .then((response) => {
      return response.data;
    })
    .catch((error) => {
      if (error.code === 'ERR_BAD_REQUEST' && error.response) {
        error.response.data
          ? alert(error.response.data.message)
          : console.log(error);
        return null;
      }
      alert(error.message);
    });
}

/**
 * Atualiza um novo post.
 * @author Luan Santos  <lvluansantos@gmail.com>
 */
export async function updatePost(newsId, data) {
  return await service
    .put(`/api-services/news/${newsId}`, data)
    .then((response) => {
      return response.data;
    })
    .catch((error) => {
      if (error.code === 'ERR_BAD_REQUEST' && error.response) {
        error.response.data
          ? alert(error.response.data.message)
          : console.log(error);
        return null;
      }
      alert(error.message);
    });
}

/**
 * Recupera as galerias.
 * @author Luan Santos  <lvluansantos@gmail.com>
 */
export async function getPhotoGallery(params = null) {
  return await service
    .get('/api-services/photos-gallery', {
      params,
    })
    .then((response) => {
      return response.data;
    })
    .catch((error) => {
      alert(error.message);
    });
}

/**
 * Recupera um post apenas para edição.
 * @author Luan Santos  <lvluansantos@gmail.com>
 */
export async function getPostEdit(newsId) {
  return await service
    .get(`/api-services/news/${newsId}/edit`)
    .then((response) => {
      return response.data;
    })
    .catch((error) => {
      alert(error.message);
    });
}

/**
 * Recupera um post apenas para edição.
 * @author Luan Santos  <lvluansantos@gmail.com>
 */
export async function deletePostEdit(newsId) {
  return await service
    .delete(`/api-services/news/${newsId}`)
    .then((response) => {
      return response.data;
    })
    .catch((error) => {
      alert(error.message);
    });
}
