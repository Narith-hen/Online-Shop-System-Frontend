import { createServer } from 'http'
import { Server } from 'socket.io'

const httpServer = createServer()

const io = new Server(httpServer, {
  cors: { origin: '*', methods: ['GET', 'POST'] },
})

httpServer.on('request', (req, res) => {
  if (req.method === 'POST' && req.url === '/push') {
    let body = ''
    req.on('data', chunk => body += chunk)
    req.on('end', () => {
      try {
        const event = JSON.parse(body)
        io.emit(event.channel, event.data)
        res.writeHead(200, { 'Content-Type': 'application/json' })
        res.end(JSON.stringify({ ok: true }))
      } catch {
        res.writeHead(400)
        res.end('Invalid JSON')
      }
    })
    return
  }
  res.writeHead(404)
  res.end('Not found')
})

const PORT = process.env.SOCKET_PORT || 3001
httpServer.listen(PORT, () => {
  console.log(`Socket.IO server running at http://localhost:${PORT}`)
})
