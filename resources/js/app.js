import './bootstrap';

// Favorite/Unfavorite functionality
document.addEventListener('DOMContentLoaded', function() {
    // Set up CSRF token for all AJAX requests
    const token = document.querySelector('meta[name="csrf-token"]');
    if (token) {
        axios.defaults.headers.common['X-CSRF-TOKEN'] = token.getAttribute('content');
    }

    // Handle favorite buttons
    document.addEventListener('click', function(e) {
        if (e.target.closest('.favorite-btn')) {
            e.preventDefault();
            const btn = e.target.closest('.favorite-btn');
            const slug = btn.dataset.articleSlug;
            
            if (!slug) return;

            // Check if user is authenticated
            if (!document.querySelector('meta[name="csrf-token"]')) {
                window.location.href = '/login';
                return;
            }

            axios.post(`/api/articles/${slug}/favorite`)
                .then(response => {
                    const article = response.data.article;
                    const icon = btn.querySelector('i');
                    const favorited = article.favorited;
                    
                    // Update button text and style
                    if (favorited) {
                        btn.classList.remove('border-green-500', 'text-green-500');
                        btn.classList.add('bg-green-500', 'text-white');
                        btn.innerHTML = `<i class="ion-heart"></i> ${article.favoritesCount}`;
                    } else {
                        btn.classList.remove('bg-green-500', 'text-white');
                        btn.classList.add('border-green-500', 'text-green-500');
                        btn.innerHTML = `<i class="ion-heart"></i> ${article.favoritesCount}`;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (error.response?.status === 401) {
                        window.location.href = '/login';
                    }
                });
        }
    });

    // Handle follow buttons
    document.addEventListener('click', function(e) {
        if (e.target.closest('.follow-btn')) {
            e.preventDefault();
            const btn = e.target.closest('.follow-btn');
            const username = btn.dataset.username;
            
            if (!username) return;

            // Check if user is authenticated
            if (!document.querySelector('meta[name="csrf-token"]')) {
                window.location.href = '/login';
                return;
            }

            axios.post(`/api/profiles/${username}/follow`)
                .then(response => {
                    const profile = response.data.profile;
                    const following = profile.following;
                    
                    // Update button text
                    if (following) {
                        btn.innerHTML = `<i class="ion-minus-round"></i> Unfollow ${username}`;
                        btn.classList.remove('border-gray-400', 'text-gray-400');
                        btn.classList.add('border-red-500', 'text-red-500');
                    } else {
                        btn.innerHTML = `<i class="ion-plus-round"></i> Follow ${username}`;
                        btn.classList.remove('border-red-500', 'text-red-500');
                        btn.classList.add('border-gray-400', 'text-gray-400');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (error.response?.status === 401) {
                        window.location.href = '/login';
                    }
                });
        }
    });

    // Handle feed toggle
    document.addEventListener('click', function(e) {
        if (e.target.closest('.feed-toggle')) {
            e.preventDefault();
            const btn = e.target.closest('.feed-toggle');
            const feedType = btn.dataset.feed;
            
            // Update active state
            document.querySelectorAll('.feed-toggle').forEach(toggle => {
                toggle.classList.remove('border-green-500', 'text-green-600', 'active');
                toggle.classList.add('border-transparent', 'text-gray-500');
            });
            
            btn.classList.remove('border-transparent', 'text-gray-500');
            btn.classList.add('border-green-500', 'text-green-600', 'active');
            
            // Load articles for the selected feed
            loadArticles(feedType);
        }
    });

    function loadArticles(feedType = 'global') {
        const container = document.getElementById('articles-container');
        if (!container) return;

        container.innerHTML = '<div class="text-center py-8"><p class="text-gray-500">Loading articles...</p></div>';

        const endpoint = feedType === 'your' ? '/api/articles/feed' : '/api/articles';
        
        axios.get(endpoint)
            .then(response => {
                const articles = response.data.articles;
                
                if (articles.length === 0) {
                    container.innerHTML = '<div class="text-center py-8"><p class="text-gray-500">No articles are here... yet.</p></div>';
                    return;
                }

                let html = '';
                articles.forEach(article => {
                    html += generateArticleHTML(article);
                });
                
                container.innerHTML = html;
            })
            .catch(error => {
                console.error('Error loading articles:', error);
                container.innerHTML = '<div class="text-center py-8"><p class="text-red-500">Error loading articles.</p></div>';
            });
    }

    function generateArticleHTML(article) {
        const authorImage = article.author.image 
            ? `<img src="${article.author.image}" class="w-8 h-8 rounded-full" alt="${article.author.username}">`
            : `<div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center">
                <span class="text-gray-600 text-sm">${article.author.username.charAt(0).toUpperCase()}</span>
               </div>`;

        const tags = article.tagList.map(tag => 
            `<span class="bg-gray-200 text-gray-700 px-2 py-1 rounded text-xs">${tag}</span>`
        ).join('');

        return `
            <article class="border-b border-gray-200 py-6">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center space-x-2">
                        <a href="/profile/${article.author.username}">
                            ${authorImage}
                        </a>
                        <div>
                            <a href="/profile/${article.author.username}" class="text-green-500 hover:underline font-medium">
                                ${article.author.username}
                            </a>
                            <p class="text-gray-500 text-sm">${new Date(article.createdAt).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</p>
                        </div>
                    </div>
                    <button class="favorite-btn border border-green-500 text-green-500 px-3 py-1 rounded text-sm hover:bg-green-500 hover:text-white transition-colors duration-200"
                            data-article-slug="${article.slug}">
                        <i class="ion-heart"></i> ${article.favoritesCount}
                    </button>
                </div>
                
                <div class="mb-4">
                    <h2 class="text-xl font-semibold mb-2">
                        <a href="/articles/${article.slug}" class="text-gray-900 hover:text-gray-600">
                            ${article.title}
                        </a>
                    </h2>
                    <p class="text-gray-600 mb-3">${article.description}</p>
                </div>
                
                <div class="flex justify-between items-center">
                    <a href="/articles/${article.slug}" class="text-gray-500 text-sm hover:underline">
                        Read more...
                    </a>
                    <div class="flex flex-wrap gap-1">
                        ${tags}
                    </div>
                </div>
            </article>
        `;
    }
});
