const { Client, LocalAuth } = require('whatsapp-web.js');
const qrcode = require('qrcode-terminal');
const express = require('express');
const bodyParser = require('body-parser');

const app = express();
app.use(bodyParser.json());

// Gunakan LocalAuth untuk simpan session otomatis
const client = new Client({
    authStrategy: new LocalAuth({ dataPath: './session' })
});

client.on('qr', (qr) => {
    console.log('Scan QR code ini dengan WhatsApp:');
    qrcode.generate(qr, { small: true });
});

client.on('ready', () => {
    console.log('WhatsApp client siap!');
});

app.post('/send-message', async (req, res) => {
    const { number, message } = req.body;

    try {
        const chatId = number + "@c.us";
        await client.sendMessage(chatId, message);
        res.send({ status: 'success', number, message });
    } catch (error) {
        res.status(500).send({ status: 'error', error: error.message });
    }
});

client.initialize();

app.listen(3000, () => {
    console.log('WA Gateway berjalan di http://localhost:3000');
});
