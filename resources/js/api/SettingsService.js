import axios from 'axios';

export default class {
    static async fetch() {
        const response = await axios.get('/api/settings');
        return response.data.data.settings;
    }

    static async store(settings) {
        const response = await axios.post('/api/settings/store', {
            settings: JSON.stringify(settings)
        });
    }
}
