<?php
$portalData = $portalData ?? require __DIR__ . '/../../Data/portal_content.php';
$portalSettings = $portalData['settings'] ?? [];
$portalCategories = $portalData['categories'] ?? [];
$currentRoute = $_GET['url'] ?? 'home';
$isAdminLogin = $currentRoute === 'admin/login';
?>
        </main>
    </div>

    <?php if (str_starts_with($currentRoute, 'admin/') && !$isAdminLogin): ?>
        </div>
    </div>
    <?php elseif (str_starts_with($currentRoute, 'admin/')): ?>
        <footer class="main-footer">
        </footer>
    <?php else: ?>
        <footer class="public-footer">
            <div class="container public-footer-main">
                <div class="footer-column">
                    <a class="footer-brand" href="<?= htmlspecialchars($routeUrl('home')) ?>">
                        <span class="brand-badge">OE</span>
                        <span><?= htmlspecialchars($portalSettings['nome_site'] ?? 'O Editorial') ?></span>
                    </a>
                    <p><?= htmlspecialchars($portalSettings['slogan'] ?? 'Jornalismo com profundidade e compromisso.') ?></p>
                    <p><?= htmlspecialchars($portalSettings['about_text'] ?? 'O Editorial é um veículo jornalístico independente comprometido com a qualidade informativa e o pluralismo de ideias.') ?></p>
                </div>

                <div class="footer-column">
                    <h4>Navegação</h4>
                    <nav class="footer-links" aria-label="Navegação do rodapé">
                        <a href="<?= htmlspecialchars($routeUrl('home')) ?>">Home</a>
                        <a href="<?= htmlspecialchars($routeUrl('publicacoes')) ?>">Publicações</a>
                        <?php $publicAccountUrl = isset($_SESSION['usuario_publico_nome']) ? $routeUrl('conta') : $routeUrl('login', ['modo' => 'entrar']); ?>
                        <a href="<?= htmlspecialchars($publicAccountUrl) ?>"><?= isset($_SESSION['usuario_publico_nome']) ? 'Minha conta' : 'Entrar' ?></a>
                        <?php $adminPortalUrl = !empty($_SESSION['usuario_logado']) ? $routeUrl('admin/posts') : $routeUrl('admin/login'); ?>
                        <a href="<?= htmlspecialchars($adminPortalUrl) ?>">Painel Admin</a>
                    </nav>
                </div>

                <div class="footer-column">
                    <h4>Contato</h4>
                    <div class="footer-links">
                        <?php $contactEmail = trim((string) ($portalSettings['contact_email'] ?? '')); ?>
                        <?php if ($contactEmail !== ''): ?>
                            <a href="mailto:<?= htmlspecialchars($contactEmail) ?>"><?= htmlspecialchars($contactEmail) ?></a>
                        <?php else: ?>
                            <span>E-mail indisponível</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </footer>
    <?php endif; ?>
</div>
<script>
var currentDateLabel = document.querySelector('[data-current-date]');
if (currentDateLabel) {
    var formatter = new Intl.DateTimeFormat('pt-BR', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
    currentDateLabel.textContent = formatter.format(new Date());
}

var carousel = document.querySelector('[data-carousel]');
if (carousel) {
    var slides = Array.from(carousel.querySelectorAll('[data-carousel-slide]'));
    var dots = Array.from(carousel.querySelectorAll('[data-carousel-dot]'));
    var prevButton = carousel.querySelector('[data-carousel-prev]');
    var nextButton = carousel.querySelector('[data-carousel-next]');
    var currentIndex = 0;
    var autoPlayId = null;

    var renderCarousel = function (index) {
        currentIndex = (index + slides.length) % slides.length;
        slides.forEach(function (slide, slideIndex) {
            slide.classList.toggle('is-active', slideIndex === currentIndex);
        });
        dots.forEach(function (dot, dotIndex) {
            dot.classList.toggle('is-active', dotIndex === currentIndex);
        });
    };

    var restartAutoPlay = function () {
        if (autoPlayId) {
            window.clearInterval(autoPlayId);
        }
        autoPlayId = window.setInterval(function () {
            renderCarousel(currentIndex + 1);
        }, 5000);
    };

    if (prevButton) {
        prevButton.addEventListener('click', function () {
            renderCarousel(currentIndex - 1);
            restartAutoPlay();
        });
    }

    if (nextButton) {
        nextButton.addEventListener('click', function () {
            renderCarousel(currentIndex + 1);
            restartAutoPlay();
        });
    }

    dots.forEach(function (dot, dotIndex) {
        dot.addEventListener('click', function () {
            renderCarousel(dotIndex);
            restartAutoPlay();
        });
    });

    renderCarousel(0);
    restartAutoPlay();
}

var adminTabs = document.querySelectorAll('.admin-tab-button[data-admin-tab]');
if (adminTabs.length) {
    var panes = Array.from(document.querySelectorAll('[data-admin-pane]'));
    var activateAdminTab = function (tabName) {
        adminTabs.forEach(function (tabButton) {
            tabButton.classList.toggle('is-active', tabButton.dataset.adminTab === tabName);
        });
        panes.forEach(function (pane) {
            pane.classList.toggle('is-active', pane.dataset.adminPane === tabName);
        });
    };

    adminTabs.forEach(function (tabButton) {
        tabButton.addEventListener('click', function () {
            activateAdminTab(tabButton.dataset.adminTab);
        });
    });

    activateAdminTab(adminTabs[0].dataset.adminTab);
}

document.querySelectorAll('[data-admin-switch]').forEach(function (switchButton) {
    switchButton.addEventListener('click', function () {
        var targetName = switchButton.dataset.adminSwitch;
        document.querySelectorAll('.admin-tab-button[data-admin-tab]').forEach(function (tabButton) {
            if (tabButton.dataset.adminTab === targetName) {
                tabButton.click();
            }
        });
    });
});

var renderAdminPagination = function (container, totalItems, currentPage, onPageChange) {
    if (!container) return;

    var totalPages = Math.max(1, Math.ceil(totalItems));
    container.innerHTML = '';

    if (totalItems <= 1) {
        return;
    }

    var createButton = function (label, page, disabled, active) {
        var button = document.createElement('button');
        button.type = 'button';
        button.className = 'admin-pagination-button' + (active ? ' is-active' : '');
        button.textContent = label;
        button.disabled = !!disabled;
        button.addEventListener('click', function () {
            if (!disabled) {
                onPageChange(page);
            }
        });
        container.appendChild(button);
    };

    createButton('Anterior', currentPage - 1, currentPage <= 1, false);

    for (var page = 1; page <= totalPages; page += 1) {
        createButton(String(page), page, false, page === currentPage);
    }

    createButton('Próxima', currentPage + 1, currentPage >= totalPages, false);
};

var usersPage = document.querySelector('[data-admin-users-page]');
if (usersPage) {
    var usersSearch = usersPage.querySelector('[data-admin-users-search]');
    var usersCount = usersPage.querySelector('[data-admin-users-count]');
    var usersPagination = usersPage.querySelector('[data-admin-users-pagination]');
    var userRows = Array.from(usersPage.querySelectorAll('[data-admin-user-row]'));
    var usersPageSize = parseInt(usersPage.dataset.adminUsersPageSize || '5', 10);
    var usersCurrentPage = 1;

    var updateUsers = function (resetPage) {
        if (resetPage) {
            usersCurrentPage = 1;
        }
        var term = usersSearch ? usersSearch.value.trim().toLowerCase() : '';
        var visibleUsers = [];

        userRows.forEach(function (row) {
            var haystack = [row.dataset.name, row.dataset.email, row.dataset.role, row.dataset.status].join(' ');
            var isVisible = term === '' || haystack.indexOf(term) !== -1;
            if (isVisible) {
                visibleUsers.push(row);
            }
        });

        var totalPages = Math.max(1, Math.ceil(visibleUsers.length / usersPageSize));
        if (usersCurrentPage > totalPages) {
            usersCurrentPage = totalPages;
        }

        var startIndex = (usersCurrentPage - 1) * usersPageSize;
        var endIndex = startIndex + usersPageSize;

        userRows.forEach(function (row) {
            row.classList.add('is-hidden');
        });

        visibleUsers.slice(startIndex, endIndex).forEach(function (row) {
            row.classList.remove('is-hidden');
        });

        if (usersCount) {
            usersCount.textContent = visibleUsers.length + (visibleUsers.length === 1 ? ' usuário encontrado' : ' usuários encontrados');
        }

        renderAdminPagination(usersPagination, visibleUsers.length / usersPageSize, usersCurrentPage, function (page) {
            usersCurrentPage = page;
            updateUsers(false);
        });
    };

    if (usersSearch) {
        usersSearch.addEventListener('input', function () {
            updateUsers(true);
        });
    }
    updateUsers(true);
}

var categoriesPage = document.querySelector('[data-admin-categories-page]');
if (categoriesPage) {
    var categoriesSearch = categoriesPage.querySelector('[data-admin-categories-search]');
    var categoriesCount = categoriesPage.querySelector('[data-admin-categories-count]');
    var categoriesPagination = categoriesPage.querySelector('[data-admin-categories-pagination]');
    var categoryCards = Array.from(categoriesPage.querySelectorAll('[data-admin-category-card]'));
    var categoryRows = Array.from(categoriesPage.querySelectorAll('[data-admin-category-row]'));
    var categoriesPageSize = parseInt(categoriesPage.dataset.adminCategoriesPageSize || '4', 10);
    var categoriesCurrentPage = 1;

    var updateCategories = function (resetPage) {
        if (resetPage) {
            categoriesCurrentPage = 1;
        }
        var term = categoriesSearch ? categoriesSearch.value.trim().toLowerCase() : '';
        var visibleSlugs = [];
        var searchableItems = categoryCards.length ? categoryCards : categoryRows;

        searchableItems.forEach(function (item) {
            var haystack = [item.dataset.name, item.dataset.slug, item.dataset.description].join(' ');
            var isVisible = term === '' || haystack.indexOf(term) !== -1;
            if (isVisible) {
                visibleSlugs.push(item.dataset.slug);
            }
        });

        var totalPages = Math.max(1, Math.ceil(visibleSlugs.length / categoriesPageSize));
        if (categoriesCurrentPage > totalPages) {
            categoriesCurrentPage = totalPages;
        }
        var startIndex = (categoriesCurrentPage - 1) * categoriesPageSize;
        var endIndex = startIndex + categoriesPageSize;
        var pageSlugs = visibleSlugs.slice(startIndex, endIndex);

        categoryCards.forEach(function (card) {
            card.classList.toggle('is-hidden', pageSlugs.indexOf(card.dataset.slug) === -1);
        });

        categoryRows.forEach(function (row) {
            row.classList.toggle('is-hidden', pageSlugs.indexOf(row.dataset.slug) === -1);
        });

        if (categoriesCount) {
            categoriesCount.textContent = visibleSlugs.length + (visibleSlugs.length === 1 ? ' categoria encontrada' : ' categorias encontradas');
        }

        renderAdminPagination(categoriesPagination, visibleSlugs.length / categoriesPageSize, categoriesCurrentPage, function (page) {
            categoriesCurrentPage = page;
            updateCategories(false);
        });
    };

    if (categoriesSearch) {
        categoriesSearch.addEventListener('input', function () {
            updateCategories(true);
        });
    }
    updateCategories(true);
}

var postsPage = document.querySelector('[data-admin-posts-page]');
if (postsPage) {
    var postsSearch = postsPage.querySelector('[data-admin-posts-search]');
    var postsCount = postsPage.querySelector('[data-admin-posts-count]');
    var postsPagination = postsPage.querySelector('[data-admin-posts-pagination]');
    var postRows = Array.from(postsPage.querySelectorAll('[data-admin-post-row]'));
    var postFilters = Array.from(postsPage.querySelectorAll('[data-admin-posts-filter]'));
    var postsPageSize = parseInt(postsPage.dataset.adminPostsPageSize || '5', 10);
    var activePostsFilter = 'todos';
    var postsCurrentPage = 1;

    var updatePosts = function (resetPage) {
        if (resetPage) {
            postsCurrentPage = 1;
        }
        var term = postsSearch ? postsSearch.value.trim().toLowerCase() : '';
        var visiblePosts = [];

        postRows.forEach(function (row) {
            var haystack = [row.dataset.title, row.dataset.slug, row.dataset.author, row.dataset.category].join(' ');
            var matchesSearch = term === '' || haystack.indexOf(term) !== -1;
            var matchesFilter = activePostsFilter === 'todos' || row.dataset.status === activePostsFilter;
            var isVisible = matchesSearch && matchesFilter;
            if (isVisible) {
                visiblePosts.push(row);
            }
        });

        var totalPages = Math.max(1, Math.ceil(visiblePosts.length / postsPageSize));
        if (postsCurrentPage > totalPages) {
            postsCurrentPage = totalPages;
        }
        var startIndex = (postsCurrentPage - 1) * postsPageSize;
        var endIndex = startIndex + postsPageSize;

        postRows.forEach(function (row) {
            row.classList.add('is-hidden');
        });

        visiblePosts.slice(startIndex, endIndex).forEach(function (row) {
            row.classList.remove('is-hidden');
        });

        if (postsCount) {
            postsCount.textContent = visiblePosts.length + (visiblePosts.length === 1 ? ' registro encontrado' : ' registros encontrados');
        }

        renderAdminPagination(postsPagination, visiblePosts.length / postsPageSize, postsCurrentPage, function (page) {
            postsCurrentPage = page;
            updatePosts(false);
        });
    };

    postFilters.forEach(function (button) {
        button.addEventListener('click', function () {
            activePostsFilter = button.dataset.adminPostsFilter || 'todos';
            postFilters.forEach(function (filterItem) {
                filterItem.classList.toggle('is-active', filterItem === button);
            });
            updatePosts(true);
        });
    });

    if (postsSearch) {
        postsSearch.addEventListener('input', function () {
            updatePosts(true);
        });
    }
    updatePosts(true);
}

var commentsPage = document.querySelector('[data-admin-comments-page]');
if (commentsPage) {
    var commentsSearch = commentsPage.querySelector('[data-admin-comments-search]');
    var commentsCount = commentsPage.querySelector('[data-admin-comments-count]');
    var commentsPagination = commentsPage.querySelector('[data-admin-comments-pagination]');
    var commentCards = Array.from(commentsPage.querySelectorAll('[data-admin-comment-card]'));
    var commentFilters = Array.from(commentsPage.querySelectorAll('[data-admin-comments-filter]'));
    var commentsPageSize = parseInt(commentsPage.dataset.adminCommentsPageSize || '4', 10);
    var activeCommentsFilter = 'todos';
    var commentsCurrentPage = 1;

    var updateComments = function (resetPage) {
        if (resetPage) {
            commentsCurrentPage = 1;
        }
        var term = commentsSearch ? commentsSearch.value.trim().toLowerCase() : '';
        var visibleComments = [];

        commentCards.forEach(function (card) {
            var haystack = [card.dataset.author, card.dataset.email, card.dataset.content, card.dataset.post].join(' ');
            var matchesSearch = term === '' || haystack.indexOf(term) !== -1;
            var matchesFilter = activeCommentsFilter === 'todos' || card.dataset.status === activeCommentsFilter;
            var isVisible = matchesSearch && matchesFilter;
            if (isVisible) {
                visibleComments.push(card);
            }
        });

        var totalPages = Math.max(1, Math.ceil(visibleComments.length / commentsPageSize));
        if (commentsCurrentPage > totalPages) {
            commentsCurrentPage = totalPages;
        }
        var startIndex = (commentsCurrentPage - 1) * commentsPageSize;
        var endIndex = startIndex + commentsPageSize;

        commentCards.forEach(function (card) {
            card.classList.add('is-hidden');
        });

        visibleComments.slice(startIndex, endIndex).forEach(function (card) {
            card.classList.remove('is-hidden');
        });

        if (commentsCount) {
            commentsCount.textContent = visibleComments.length + (visibleComments.length === 1 ? ' comentário encontrado' : ' comentários encontrados');
        }

        renderAdminPagination(commentsPagination, visibleComments.length / commentsPageSize, commentsCurrentPage, function (page) {
            commentsCurrentPage = page;
            updateComments(false);
        });
    };

    commentFilters.forEach(function (button) {
        button.addEventListener('click', function () {
            activeCommentsFilter = button.dataset.adminCommentsFilter || 'todos';
            commentFilters.forEach(function (filterItem) {
                filterItem.classList.toggle('is-active', filterItem === button);
            });
            updateComments(true);
        });
    });

    try {
        var urlParams = new URLSearchParams(window.location.search);
        var initialFilter = (urlParams.get('filtro') || '').trim().toLowerCase();
        if (initialFilter && ['todos', 'aprovado', 'pendente', 'rejeitado'].indexOf(initialFilter) !== -1) {
            activeCommentsFilter = initialFilter;
            commentFilters.forEach(function (filterItem) {
                filterItem.classList.toggle('is-active', (filterItem.dataset.adminCommentsFilter || 'todos') === activeCommentsFilter);
            });
        }
    } catch (e) {
        // ignore
    }

    if (commentsSearch) {
        commentsSearch.addEventListener('input', function () {
            updateComments(true);
        });
    }
    updateComments(true);
}
</script>
</body>
</html>
