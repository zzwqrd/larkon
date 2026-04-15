import sys

with open('/Users/mac/Downloads/Larkon_Laravel/Larkon/resources/views/layouts/partials/main-nav.blade.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

new_lines = []
skip = False
for i, line in enumerate(lines):
    if '<li class="nav-item">' in line and lines[i-2].find('messages.general') != -1:
        skip = True
        new_lines.append('            {!! \\App\\Traits\\SideBarTrait::sidebarButtons() !!}\n')
    
    if skip and '</ul>' in line and lines[i+1].find('</div>') != -1 and '</div>' in lines[i+2]:
        skip = False

    if not skip:
        new_lines.append(line)

with open('/Users/mac/Downloads/Larkon_Laravel/Larkon/resources/views/layouts/partials/main-nav.blade.php', 'w', encoding='utf-8') as f:
    f.writelines(new_lines)
