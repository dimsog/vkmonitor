import axios from "axios";

export default class {
    static async check(token) {
        const response = await axios.post('/api/access-token/check', {
            token
        });
        return response.data.data.result;
    }
}
