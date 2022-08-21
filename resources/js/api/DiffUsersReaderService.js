import axios from "axios";

export default class {
    static async fetch(groupId, page = 1) {
        const response = await axios.get('/api/users/' + groupId + '/' + page);
        if (response.data.success === false) {
            throw new Error(response.data.text);
        }
        return response.data.data.diff;
    }
}
