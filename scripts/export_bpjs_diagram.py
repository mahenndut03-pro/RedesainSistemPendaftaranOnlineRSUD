from PIL import Image, ImageDraw, ImageFont
import os

# Output path
out_dir = os.path.join(os.path.dirname(__file__), '..', 'diagrams')
os.makedirs(out_dir, exist_ok=True)
out_path = os.path.join(out_dir, 'diagramflowBPJS.png')

# Diagram nodes
nodes = ["Input BPJS", "Validasi", "Cek Kuota", "Verifikasi", "Nomor Antrian"]

# Image params
padding = 20
box_padding_x = 24
box_padding_y = 12
spacing = 40
font = ImageFont.load_default()

# Measure boxes using ImageDraw.textbbox for compatibility
tmp_img = Image.new('RGB', (1,1))
tmp_draw = ImageDraw.Draw(tmp_img)
box_widths = []
heights = []
for n in nodes:
    bbox = tmp_draw.textbbox((0,0), n, font=font)
    tw = bbox[2] - bbox[0]
    th = bbox[3] - bbox[1]
    box_widths.append(tw + box_padding_x*2)
    heights.append(th + box_padding_y*2)
box_height = max(heights)

total_width = sum(box_widths) + spacing * (len(nodes)-1) + padding*2
total_height = box_height + padding*2 + 40

# Create image
img = Image.new('RGB', (total_width, total_height), color='white')
d = ImageDraw.Draw(img)

# Draw nodes and arrows horizontally
x = padding
y = padding
for i, node in enumerate(nodes):
    w = box_widths[i]
    h = box_height
    rect = [x, y, x+w, y+h]
    # box
    d.rounded_rectangle(rect, radius=8, outline='black', width=2, fill='#f0f7ff')
    # text
    bbox_text = d.textbbox((0,0), node, font=font)
    tw = bbox_text[2] - bbox_text[0]
    th = bbox_text[3] - bbox_text[1]
    tx = x + (w - tw)/2
    ty = y + (h - th)/2
    d.text((tx, ty), node, fill='black', font=font)

    # arrow to next
    if i < len(nodes)-1:
        # start at center right
        sx = x+w
        sy = y + h/2
        ex = x+w + spacing
        ey = sy
        # line
        d.line([(sx, sy), (ex, ey)], fill='black', width=3)
        # arrow head
        ah = 8
        arrow = [(ex, ey), (ex-ah, ey-ah), (ex-ah, ey+ah)]
        d.polygon(arrow, fill='black')
    x += w + spacing

# Optional: start node and end node markers
# start circle
start_x = padding - 40 + 20
start_y = y + box_height/2
r = 10
d.ellipse([(padding-40, start_y-r), (padding-40+2*r, start_y+r)], outline='black', width=2, fill='black')
# arrow from start to first box
d.line([(padding-40+2*r, start_y), (padding, start_y)], fill='black', width=3)
arrow = [(padding, start_y), (padding-2, start_y-7), (padding-2, start_y+7)]
d.polygon(arrow, fill='black')

# final circle
end_x = total_width - padding + 40 - 20
end_y = start_y
d.ellipse([(total_width - padding + 40 - 2*r, end_y-r), (total_width - padding + 40, end_y+r)], outline='black', width=2, fill='black')
# arrow from last box to end
last_box_right = padding + sum(box_widths) + spacing*(len(nodes)-1) - spacing
d.line([(last_box_right + box_widths[-1], start_y), (total_width - padding + 40 - 2*r, start_y)], fill='black', width=3)

# Save
img.save(out_path)
print('Saved', out_path)
