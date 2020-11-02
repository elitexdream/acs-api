export default {
	// authUser(){
 //    return axios.get('/user').then(response =>  {
 //      return response.data
 //    }).catch(error => {
 //      return error.response.data
 //    });
	// },
	// authUserOccupation() {
	// 	return axios.get('/user-occupation').then(response => {
	// 		return response.data
	// 	}).catch(error => {
	// 		return error.response.data
	// 	});
	// },

	check(){
		return axios.post('/auth/check').then(response =>  {
			return !!response.data.authenticated;
		}).catch(error =>{
			return error.response.data.authenticated;
		});
	},

	// getFilterURL(data){
	//     let url = '';
	//     $.each(data, function(key,value) {
	//         url += (value) ? '&'+key+'='+encodeURI(value) : '';
	//     });
	//     return url;
	// },
	// getAvatar(user) {
	// 	return server_url + 'uploads/users/' + user.avatar;
	// },
	// getFilterURL(data) {
	// 	let url = '';
	// 	Object.keys(data).map(function (key, value) {
	// 		url += (data[key]) ? '&' + key + '=' + data[key] : '';
	// 	});
	// 	return url;
	// },
	// getLanguages(){
	// 	return [{
	// 			value: 'en',
	// 			text: 'English'
	// 		},
	// 		{
	// 			value: 'es',
	// 			text: 'Español'
	// 		},
	// 	]
	// }
}
