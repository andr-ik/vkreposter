VK.init({
    apiId: 5526170
});

VK.Auth.login(function(data){
    console.log('auth', data);
}, 8192);
