import axios from "axios";

export default class {
    static async fetch() {
        const response = await axios.get('/api/user/read');
        return response.data.data.user;
    }
}
