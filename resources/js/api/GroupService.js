import axios from "axios";

export default class {
    static async add(vkGroupLink) {
        const response = await axios.post('/api/group/create', {
            vk_group_link: vkGroupLink,
        });
        if (response.data.success === false) {
            throw new Error(response.data.text);
        }
        return response.data.data.group;
    }
}
