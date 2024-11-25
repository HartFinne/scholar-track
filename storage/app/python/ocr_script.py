from PIL import Image
import sys
import easyocr
import os

def split_and_enlarge(image_path, enlargement_factor=2):
    image = Image.open(image_path)
    width, height = image.size
    mid_x = width // 2

    # Split into two halves
    left_half = image.crop((0, 0, mid_x, height))
    right_half = image.crop((mid_x, 0, width, height))

    # Enlarge both halves
    left_enlarged = left_half.resize((mid_x * enlargement_factor, height * enlargement_factor))
    right_enlarged = right_half.resize((mid_x * enlargement_factor, height * enlargement_factor))

    return left_enlarged, right_enlarged

def perform_ocr(image):
    reader = easyocr.Reader(['en', 'tl'], gpu=False)  # Include Tagalog
    result = reader.readtext(image, detail=0)
    return "\n".join(result)

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("Usage: python ocr_script.py <image_path>")
        sys.exit(1)

    image_path = sys.argv[1]
    base_name, ext = os.path.splitext(os.path.basename(image_path))

    # Split and enlarge
    left, right = split_and_enlarge(image_path)

    # Save split images temporarily for OCR
    left_path = f"{base_name}_left{ext}"
    right_path = f"{base_name}_right{ext}"
    left.save(left_path)
    right.save(right_path)

    # Perform OCR
    print("Left Image OCR Result:")
    print(perform_ocr(left_path))

    print("\nRight Image OCR Result:")
    print(perform_ocr(right_path))

    # Cleanup temporary files
    os.remove(left_path)
    os.remove(right_path)
