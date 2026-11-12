import os
import re

directories = [
    r"c:\laragon\www\techyknowloadeia1\resources\views\dashboards",
    r"c:\laragon\www\techyknowloadeia1\resources\js\components\bhw",
    r"c:\laragon\www\techyknowloadeia1\resources\js\components\resident"
]

def remove_comments(content):
    # Remove HTML comments <!-- ... --> (non-greedy)
    # Ignore if it has DOCTYPE
    content = re.sub(r'<!--(?!<!DOCTYPE).*?-->', '', content, flags=re.DOTALL)
    
    # Remove Blade comments {{-- ... --}} (non-greedy)
    content = re.sub(r'\{\{--.*?--\}\}', '', content, flags=re.DOTALL)
    
    # For // comments, we must be careful about URLs (http://, https://)
    # We match // not preceded by : or ' or "
    content = re.sub(r'(?<![:\'"])\/\/.*', '', content)
    
    # Remove multi-line JS/CSS comments /* ... */
    content = re.sub(r'/\*.*?\*/', '', content, flags=re.DOTALL)
    
    # Remove extra blank lines
    content = re.sub(r'\n\s*\n\s*\n', '\n\n', content)
    
    return content

for d in directories:
    if not os.path.exists(d):
        continue
    for root, dirs, files in os.walk(d):
        for file in files:
            filepath = os.path.join(root, file)
            with open(filepath, 'r', encoding='utf-8') as f:
                original = f.read()
            
            new_content = remove_comments(original)
            
            if new_content != original:
                with open(filepath, 'w', encoding='utf-8') as f:
                    f.write(new_content)
                print(f"Removed comments from {filepath}")
