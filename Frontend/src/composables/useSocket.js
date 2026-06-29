import { io } from 'socket.io-client'

let socket = null

export function useSocket() {
  function connect() {
    if (socket?.connected) return socket
    const wsUrl = import.meta.env.VITE_SOCKET_URL || 'http://localhost:3001'
    socket = io(wsUrl, { transports: ['websocket', 'polling'] })
    socket.on('connect', () => console.log('Socket connected'))
    socket.on('connect_error', (err) => console.error('Socket error:', err.message))
    return socket
  }

  function disconnect() {
    if (socket) {
      socket.disconnect()
      socket = null
    }
  }

  function on(event, handler) {
    if (socket) socket.on(event, handler)
  }

  function off(event, handler) {
    if (socket) socket.off(event, handler)
  }

  return { connect, disconnect, on, off, socket: () => socket }
}
