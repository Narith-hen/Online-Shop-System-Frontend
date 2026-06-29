const API_BASE = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000'

function authHeaders() {
  const token = localStorage.getItem('token')
  return token ? { Authorization: `Bearer ${token}` } : {}
}

async function request(endpoint, options = {}) {
  const url = `${API_BASE}${endpoint}`
  const config = {
    headers: {
      Accept: 'application/json',
      'Content-Type': 'application/json',
      ...authHeaders(),
      ...options.headers,
    },
    ...options,
  }
  if (config.body && typeof config.body === 'object' && !(config.body instanceof FormData)) {
    config.body = JSON.stringify(config.body)
  }
  const res = await fetch(url, config)
  if (!res.ok) {
    const err = new Error(`API error: ${res.status}`)
    err.status = res.status
    err.data = await res.json().catch(() => null)
    throw err
  }
  return res.json()
}

export function get(endpoint, params) {
  const query = params ? '?' + new URLSearchParams(params).toString() : ''
  return request(`${endpoint}${query}`, { method: 'GET' })
}

export function post(endpoint, data) {
  return request(endpoint, { method: 'POST', body: data })
}

export function put(endpoint, data) {
  return request(endpoint, { method: 'PUT', body: data })
}

export function del(endpoint) {
  return request(endpoint, { method: 'DELETE' })
}

export function upload(endpoint, formData) {
  return request(endpoint, {
    method: 'POST',
    body: formData,
    headers: {}, // let browser set Content-Type
  })
}

export default { get, post, put, del, upload }
